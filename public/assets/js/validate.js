const imageType = ["image/jpeg","image/jpg","image/png"];
const videoType = ["video/mp4","video/webm"];
const jsonType = ["application/json"];

function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function buildSlug(value) {
    let n = value.replace(/[\s]/g,'-');
    n = n.replace(/[^\wก-๙\-]/g,'');
    return n;
}

function validateFormName (value) {
    return value.replace(/[^a-zA-Z0-9]/g,'-');
}

function isBlank(value) {
    if(value.trim().length > 0)
    {
        return false;
    }
    return true;
}

function isName(value) {
    let regex = /^[^0-9 _!¡?÷?¿/\\+=@#$%ˆ&*(){}|~<>;:[\]]{2,}$/;
    return regex.test(value);
}

function isValidUrl(urlString) {
    var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
  '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
    return !!urlPattern.test(urlString);
}


function isPhone(value) {
    let checkPlus = /^[+]{1}/;
    let regex = /^[0]{1}?(\d{2})?[-\s\.]?(\d{3})?[-\s\.]?(\d{4})$$/;//no +
    if(checkPlus.test(value)){
        // regex = /^[\+]?[0-9]{4}?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4}$/im;
        regex = /^[\+]?(\d{4})?[-\s\.]?(\d{3})[-\s\.]?(\d{4})$/;
    }
    return regex.test(value);
}

function isEmail(value) {
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(value);
}

function isCost(value) {
    let regex = /(?:\d*\.\d{1,2}|\d+)$/;
    return regex.test(value);
}

function isNumber(value) {
    let regex = /^\d+$/;
    return regex.test(value);
}

function checkFileCv(id){
    let files = $('#'+id)[0].files;
    let validImageTypes = ["image/jpeg", "image/png", "application/pdf"];
    if ($.inArray(files[0]['type'], validImageTypes) < 0) {
        return false;
    }
    return true;
}

function checkID(id){
    id = id.replace(/-/g, "");
    if(! isNumber(id)) return false;
    if(id.substring(0,1)== 0) return false;
    if(id.length != 13) return false;
    for(i=0, sum=0; i < 12; i++)
        sum += parseFloat(id.charAt(i))*(13-i);
    if((11-sum%11)%10!=parseFloat(id.charAt(12))) return false;
    return true;
}

function checkpassword(id){
    let password = $('#'+id).val().trim();
    let obj = $('#'+id).parent()
    let result = true;
    //check password length
    if(password.length < 5){
        $('#'+id).removeClass('is-valid');
        $('#'+id).addClass('is-invalid');
        $(obj).find('.invalid-feedback').html('รหัสผ่านห้ามน้อยกว่า 5 ตัวอักษร');
        return false;
    }else{
        let number = /([0-9])/;
        let alphabets = /([a-zA-Z])/;
        if (password.match(number) && password.match(alphabets)) {
            $('#'+id).removeClass('is-invalid');
            return true;
        } else {
            $('#'+id).addClass('is-invalid');
            $(obj).find('.invalid-feedback').html('รหัสผ่านต้องมีตัวอักษรตัวใหญ่ 1 ตัว ตัวเล็ก 1 ตัว ตัวเลข 1 ตัว เฉพาะภาษาอังกฤษ');
            return false;
        }
    }
}

const isObjectEmpty = (objectName) => {
    return (
        objectName &&
        Object.keys(objectName).length === 0 &&
        objectName.constructor === Object
    );
};

const objectData = (objectName,key) => {
    let result = '';
    if (key in objectName){
        result = objectName[key];
    }
    return result;
};

const objectDataBoolean = (objectName,key) => {
    let result = false;
    if (key in objectName){
        result = objectName[key];
    }
    return result;
};

function radioCheck(value){
    if(typeof $(`input[name=${value}]:checked`).val() === 'undefined'){
        return false;
    }
    return true;
}

function checkboxCheck(checkclass,min = 1,max = 1){
    let result = countCheckbox(checkclass);
    if(result >= min) {
        if(result <= max){
            return true;
        }
    }
    return false;
}

const countCheckbox = (checkclass) => {
    let checknum = 0;
    $.each($('.'+checkclass),function(index,value){
        if($(value).prop('checked')){
          checknum++
        }
    })
    return checknum;
}

function compairdate(startinput,endinput) {
    const [dateValues, timeValues] = startinput.split(' ');
    const [day, month, year] = dateValues.split('/');
    const [hours, minutes, seconds] = timeValues.split(':');
    const startdate = new Date(+year, +month - 1, +day, +hours, +minutes, '00');

    const [edateValues, etimeValues] = endinput.split(' ');
    const [eday, emonth, eyear] = edateValues.split('/');
    const [ehours, eminutes, eseconds] = etimeValues.split(':');
    const enddate = new Date(+eyear, +emonth - 1, +eday, +ehours, +eminutes, '00');
    //  👇️️ Sat Sep 24 2022 07:30:14
    let result = true;
    if(startdate > enddate) {
        result = false;
    }
    return result;
}

function checkUploadSelectFile(objectel) {
    if (objectel.length > 0) {
        return true;
    } else {
        return false;
    }
}

function checkUploadFile(objectel,fileTypes) {
    if ($.inArray(objectel[0]['type'], fileTypes) < 0) {
        return false;
    }
    return true;
}

function checkUploadFileSize(objectel, size) {
    size = parseInt(size)*1048576;//1048576 = 1MB
    if (objectel[0]['size'] > parseInt(size)) {
        return false;
    }
    return true;
}

function checkUploadFileType(objectel,filetypes) {
    if ($.inArray(objectel[0]['type'].split("/").slice(-1)[0].toLowerCase(), filetypes.split(",")) < 0) {
        return false;
    }
    return true;
}
