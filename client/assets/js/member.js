'use strict';
const captureURL = new URL('http://localhost:24008'),
      captureStat = document.getElementById('capture-stat'),
      startCapture = document.getElementById('start-reg'),
      cancelCapture = document.getElementById('cancel-reg'),
      progressBar = document.querySelector('#template-quality'),
      fingerImage = document.querySelector('#finger-image'),
      captureCount = document.querySelector('#capture-count'),
      form = document.forms['form-member'];
let enroll = null,
    canceled = false,
    enrollIndex = 0;

(async () => {

    startCapture.addEventListener('click', async e => {
        // init local service 
        const init = await captureInit();
        if(init.ret < 0) {
            captureStat.innerHTML = "Service fingerprint belum terinstall, Silahkan install terlebih dahulu";
            throw new Error("Service fingerprint belum terinstall, Silahkan install terlebih dahulu");
        }
        
        // Start enroll
        const start = await captureStart();
        if(start.ret < 0 && start.ret !== -2005) {
            captureStat.innerHTML = "Alat tidak terhubung";
            throw new Error("Alat tidak terhubung");
        }

        if(start.ret === -2005) {
            captureStat.innerHTML = "Operasi lain sedang berjalan, klik tombol batalkan untuk batalkan proses";
            throw new Error("Operasi Sedanga Berjalan");
        }

        e.target.disabled = true;

        // capture images and template 
        enroll = setInterval(async () => await fingerCapture(), 1000);
    });

    cancelCapture.addEventListener('click', async e => {
        var cc = await captureCancel();
        canceled = true;
        clearInterval(enroll);
        startCapture.removeAttribute('disabled');
        progressBar.value = 0;
        progressBar.hidden = true;
        captureStat.innerHTML = '';
        captureCount.innerHTML = '';
        enrollIndex = 0;
        fingerImage.removeAttribute('src');
    });

    async function fingerCapture() {
        try
        {
            canceled = false;
            let fpImage = await captureImage();
            // if process was canceled
            if(canceled)
            {
                clearInterval(enroll);
                canceled = false;
                return;
            }

            // if return value is -2006 then it's mean empty so just return
            if(fpImage.ret === -2006 ) return;
            // if getImage return < 0 and not -2006 then capture has failed
            if(fpImage.ret < 0 && fpImage.ret !== -2006)
            {
                var cancelEvent= new Event('click');
                cancelCapture.dispatchEvent(cancelEvent);
                return;
            } 
            // if success and data is not empty then capture must repeat 3x untill template extracted 
            if(fpImage.data)
            {
                progressBar.hidden = false;
                let data = fpImage.data;

                enrollIndex = data.enroll_index;
                progressBar.value = data.quality ;
                fingerImage.src = 'data:image/jpg;base64,' + data.jpg_base64;
                captureCount.innerHTML = enrollIndex;
                // if enrool_index == 3 then enrol is success;
                if(enrollIndex == 3)
                {
                    clearInterval(enroll);
                    const tpl = await captureTemplate();
                    form['fp-template'].value = tpl.data.template;
                    captureStat.innerHTML = 'Pengambilan sidik jari berhasil';
                    setTimeout(() => {
                        var cancelEvent = new Event('click');
                        cancelCapture.dispatchEvent(cancelEvent);
                    }, 1500);
                   
                }
            }


        }
        catch(err)
        {

        }
    }
})();

/**
 * Check / Init Fingerprint Capture service 
 * 
 * @returns response json
 */
const captureInit = async () => {

    try
    {
        const url = new URL("ISSOnline/info", captureURL);
        const f = await fetch(url);
        const j = await f.json();
        
        return j;
    }
    catch(err)
    {

    }
}

/**
 * Start capture fingerprint
 * 
 * @returns response json
 */
const captureStart = async () => {

    try
    {
        let url = new URL("ISSOnline/beginCapture", captureURL);
        url.searchParams.append('type', 1);
        const f = await fetch(url);
        const j = await f.json();
        
        return j;
    }
    catch(err)
    {

    }
}

/**
 * capture image from fp reader
 * 
 * @returns response json
 */
const captureImage = async () => {
    try
    {
        let url = new URL("ISSOnline/getImage", captureURL);
        const f = await fetch(url);
        const j = await f.json();
        
        return j;
    }
    catch(err)
    {

    }
}

/**
 * capture template when capture image is success
 * 
 * @returns response json
 */
const captureTemplate = async () => {
    try
    {
        let url = new URL("ISSOnline/getTemplate", captureURL);
        const f = await fetch(url);
        const j = await f.json();
        
        return j;
    }
    catch(err)
    {

    }
}
/**
 * cCancel capture and stop process
 * 
 * @returns response json
 */
const captureCancel = async () => {
    try
    {
        let url = new URL("ISSOnline/cancelCapture/", captureURL);
        const f = await fetch(url);
        const j = await f.json();
        
        return j;
    }
    catch(err)
    {

    }
}