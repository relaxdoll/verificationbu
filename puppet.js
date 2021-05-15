const puppeteer = require('puppeteer');
const cheerio = require('cheerio');

const sleep = async (ms) => {
    if (ms <= 0) return;
    return new Promise(resolve => {
        setTimeout(resolve, ms)
    });
};

async function forward() {

    const browser = await puppeteer.launch({headless: false});
    const page = await browser.newPage();

    await page.goto('https://maintenance.eecl.co.th/liff?tab=forward&id=1000');

    await loginLine('fasttrack@eecl.co.th', 'eecline1');

    const $ = cheerio.load(await page.content());

    const newPagePromise = new Promise(x => page.once('popup', x));

    // let group = $('p').text();

    await startShareTargetPicker();

    // declare new tab /window,
    const newPage = await newPagePromise;

    await clickGroupButton();

    await selectGroup('VRS', false);

    await selectGroup('GGC');

    // await newPage.close();
    // await newPage.click('button.c-button');
    // }
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

    async function selectGroup(name, reset = true) {

        if (reset) {
            await newPage.click('.c-textinput__reset');
        }

        console.log('Search for group "'+name+'"');

        await newPage.type('input.c-textinput__input', name);


        await newPage.waitForSelector('.p-userList__item__content__name', {
            visible: true,
        });

        console.log('Selecting group "'+name+'"');

        await newPage.click('.p-userList__item__content__name');

        console.log('Group "'+name+'" selected.');
    }

}

forward();
