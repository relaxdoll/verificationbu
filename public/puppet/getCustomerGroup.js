const puppeteer = require('puppeteer');
const cheerio = require('cheerio');
const fs = require('fs');

const sleep = async (ms) => {
    if (ms <= 0) return;
    return new Promise(resolve => {
        setTimeout(resolve, ms)
    });
};


let data = null;

const fileName = process.argv[2] + '.json';

// const groupPath = './line_groups/' + fileName;
const groupPath = 'public/puppet/line_groups/' + fileName;
// const groupPath = './puppet/line_groups/' + fileName;


let search = null;

getFileData();


setTimeout(async function () {
    await closeNode();
}, 300000);

console.log('Searching for key: ' + search);

forward();

async function closeNode() {
    data.status = 'off';
    await fs.writeFileSync(groupPath, JSON.stringify(data));
    await process.exit(200);
}

function watch() {
    console.log(`Watching for file changes on ${groupPath}`);

    fs.watchFile(groupPath, (curr, prev) => {
        getFileData();
    });
}

function getFileData() {
    let rawdata = fs.readFileSync(groupPath);
    data = JSON.parse(rawdata);
    search = data.search;
    data.status = 'on';
}

async function forward() {

    const browser = await puppeteer.launch({headless: true});
    const page = await browser.newPage();

    await page.goto('https://maintenance.eecl.co.th/getCustomerGroup');

    await page.waitForSelector('input[name=tid]');

    const newPagePromise = new Promise(x => page.once('popup', x));

    await loginLine('fasttrack@eecl.co.th', 'eecline1');
    // await loginLine('p.worakorn@gmail.com', 'ru;idi');

    const $ = cheerio.load(await page.content());


    // let group = $('p').text();

    // await startShareTargetPicker();

    // declare new tab /window,
    const newPage = await newPagePromise;

    const code = await page.$(".code");
    const text = await page.evaluate(code => code.textContent, code);

    console.log('code: ' + text);

    await clickGroupButton();

    await getGroup();

    fs.watchFile(groupPath, (curr, prev) => {
        console.log('File Changed');
        getFileData();
        if (data.forceClose) {
            closeNode();
        }
        if (data.reEntry) {
            console.log('Re-entry detected.');
            getGroup(true);
        } else {
            if (data.share) {
                getGroup(true, true);
            } else {
                console.log('Re-entry not detected.');
            }
        }
    });

    async function clickShareButton() {
        let shareButton = true;

        while (shareButton) {

            console.log('finding share button');

            await newPage.waitForSelector('button.c-button', {
                visible: true,
                timeout: 5000,
            })
                .then(async () => {
                    console.log('share button detected.');
                    console.log('Attempt share..');
                    await newPage.click('button.c-button');

                    return true
                })
                .catch(async(e) => {

                    shareButton = false;
                    console.log('share button undetected');

                    data.share = 'off';

                    // await closeNode();

                });
            await sleep(2000);
        }
    }

    async function shareMessage() {

        await selectGroup();

        await clickShareButton();

        await closeNode();

    }

    async function selectGroup() {

        let groupNotSelected = true;

        while (groupNotSelected) {

            console.log('Selecting group "' + search + '"');

            newPage.click('.p-userList__item__content__name');

            await newPage.waitForSelector('.l-chosenItems', {
                visible: true,
                timeout: 1000,
            })
                .then(async () => {
                    groupNotSelected = false;

                    console.log('Group "' + search + '" selected.');

                    return true
                })
                .catch((e) => {

                    console.log('Retry Selecting..');

                    newPage.click('.p-userList__item__content__name');

                    return false;

                });
            await sleep(200);
        }

    }

    async function getGroup(reset = false, share = false) {

        if (await searchGroup(search, reset)) {

            await getGroupName();

            if (share) {

                return await shareMessage();
            }

            await getGroupImages();

            data.updated = true;
            data.hasResult = true;
            data.reEntry = false;
            data.code = text;

            fs.writeFileSync(groupPath, JSON.stringify(data));
        } else {

            data.avatars = [];
            data.groups = [];
            data.updated = true;
            data.hasResult = false;
            data.reEntry = false;

            fs.writeFileSync(groupPath, JSON.stringify(data));
        }

        console.log(data);
    }

    async function getGroupImages() {
        data.avatars = [];
        data.avatars = await newPage.$$eval('img[src]', imgs => imgs.map(img => img.getAttribute('src')));
        console.log('Group Image saved');
    }

    async function getGroupName() {
        data.groups = [];
        groupNames = await newPage.$$('.p-userList__item__content__name');

        for await (const groupName of groupNames) {
            let temp = await groupName.getProperty('innerText');
            temp = await temp.jsonValue();

            await data.groups.push(temp);
        }
        console.log('Group Name saved');
    }

    async function loginLine(user, password) {


        console.log('Logging into LINE.');

        await page.type('input[name=tid]', user);
        await page.type('input[name=tpasswd]', password);
        await page.click('.MdBtn01');

        await Promise.all([
            await page.waitForNavigation({
                waitUntil: 'networkidle0'
            }),
        ]);

        console.log('Logged in successfully.');
    }

    async function clickGroupButton() {

        let groupButton = await newPage.$x("//span[contains(text(), 'Groups')]");

        while (groupButton.length === 0) {

            groupButton = await newPage.$x("//span[contains(text(), 'Groups')]");

            await sleep(1000);

            console.log('Page still loading. Wait for 1 second,');

        }

        console.log('Button founded, expanding Groups for selection');

        await groupButton[0].click();

    }

    async function startShareTargetPicker() {
        await page.click('.liff-btn');

        console.log('Initiate LINE\'s share target picker.');
    }

    async function searchGroup(name, reset = true) {

        if (reset) {
            let clearButton = true;

            while (clearButton) {

                console.log('finding clear button');

                await newPage.waitForSelector('.c-textinput__reset', {
                    visible: true,
                    timeout: 10,
                })
                    .then(async () => {
                        console.log('Clear button detected.');
                        console.log('Attempt clear..');
                        await newPage.click('.c-textinput__reset');

                        return true
                    })
                    .catch((e) => {

                        clearButton = false;
                        console.log('Clear button undetected, moving on..');

                        return false
                    });

            }
        }

        console.log('Search for group "' + name + '"');

        await newPage.type('input.c-textinput__input', name);


        return await newPage
            .waitForSelector('.p-userList__item__content__name', {
                visible: true,
                timeout: 3000,
            })
            .then(() => {
                // console.log('Selecting group "' + name + '"');

                // newPage.click('.p-userList__item__content__name');

                // console.log('Group "' + name + '" selected.');

                // newPage.click('button.c-button');
                //
                // return process.exit(200);
                console.log('Group found');

                return true
            })
            .catch((e) => {

                // return process.exit(500);

                console.log('Group not found');

                return false
            });

    }

}

