'use strict';
const form = document.forms['form-device'],
      btnConnect = document.querySelector('#btn-connect');


btnConnect.addEventListener('click', async e => {
    const conn = await connect();
    form['sn'].value = conn.data.sn;
});

/**
 * Test device connection
 * 
 * @returns {*}
 */
const connect = async () => {
    try
    {
        const formData = new FormData(form);
        const body = new URLSearchParams(Object.fromEntries(formData.entries()));
        console.log(body);

        const url = new URL('device_connect.php', base);
        const f = await fetch(url, {
            method: 'POST',
            body: body,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        const j = await f.json();

        return j;
    }
    catch(err)
    {
        console.log(err);
    }
}