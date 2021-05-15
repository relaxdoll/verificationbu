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

// const fileName = process.argv[2] + '.json';
//
// // const groupPath = './line_groups/' + fileName;
// const groupPath = 'public/puppet/line_groups/' + fileName;
// const groupPath = './puppet/line_groups/' + fileName;


// let search = null;
//
// getFileData();


// setTimeout(async function () {
//     await closeNode();
// }, 300000);

// console.log('Searching for key: ' + search);

forward();

async function closeNode() {
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

    await page.goto('https://maintenance.eecl.co.th/refuelJob');

    await page.waitForSelector('input[name=tid]');

    await loginLine('fasttrack@eecl.co.th', 'eecline1');

    let totalElement = await page.$(".total");
    let total = await (await totalElement.getProperty('textContent')).jsonValue();

    await checkJob(total);


    // declare new tab /window,

    //
    //
    // fs.watchFile(groupPath, (curr, prev) => {
    //     console.log('File Changed');
    //     getFileData();
    //     if (data.forceClose) {
    //         closeNode();
    //     }
    //     if (data.reEntry) {
    //         console.log('Re-entry detected.');
    //         getGroup(true);
    //     } else {
    //         if (data.share) {
    //             getGroup(true, true);
    //         } else {
    //             console.log('Re-entry not detected.');
    //         }
    //     }
    // });

    async function checkJob(total) {

        await page.click('.refresh');
        let totalElement = await page.$(".total");
        total = await (await totalElement.getProperty('textContent')).jsonValue();

        console.log('Total waiting jobs: ' + total);

        while (total > 0) {

            await sendFlex(total - 1);

            await page.click('.sent' + (total - 1));

            await sleep(2000);

            await page.click('.refresh');

            await sleep(2000);

            total = await (await totalElement.getProperty('textContent')).jsonValue();

            await sleep(2000);

            console.log('Total waiting jobs: ' + total);

        }

        await sleep(5000);

        await checkJob(total);
    }

    async function sendFlex(job) {

        console.log('sending flex: 1/' + (job + 1));

        let groupElement = await page.$(".group" + job);
        let groupName = await (await groupElement.getProperty('textContent')).jsonValue();

        console.log('group name: ' + groupName);

        let newPagePromise = new Promise(x => page.once('popup', x));

        let $ = cheerio.load(await page.content());

        await page.click('.button' + job);

        let newPageResult = await timeoutPromise(newPagePromise, 10000);

        if (newPageResult === 1) {
            console.log('share picker not founded, Closing node');
            await closeNode();
        }

        let newPage = await newPagePromise;

        await clickGroupButton(newPage);

        await searchGroup(newPage, groupName, false);

        await selectGroup(newPage, groupName);

        await clickShareButton(newPage);

    }

    async function clickShareButton(newPage) {
        let shareButton = true;

        while (shareButton) {

            console.log('finding share button');

            await newPage.waitForSelector('button.c-button', {
                visible: true,
                timeout: 10,
            })
                .then(async () => {
                    console.log('share button detected.');
                    console.log('Attempt share..');
                    await newPage.click('button.c-button');

                    return true
                })
                .catch(async (e) => {

                    shareButton = false;
                    console.log('share button undetected, closing..');

                    // data.share = 'off';

                    // await closeNode();

                });
            await sleep(200);
        }
    }

    async function shareMessage() {

        await selectGroup();

        await clickShareButton();

    }

    async function selectGroup(newPage, search) {

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

    async function clickGroupButton(newPage) {

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

    async function timeoutPromise(promise, ms) {
        let timeout = new Promise(function (resolve, reject) {
            setTimeout(resolve, ms, 1);
        });
        let result = Promise.race([promise, timeout]).then(function (value) {
            return value;
        });
        return result;
    }

    async function searchGroup(newPage, name, reset = true) {

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

