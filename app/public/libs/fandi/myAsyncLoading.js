//Calon tambahan: notifbar: http://red-team-design.com/cool-notification-messages-with-css3-jquery/
$(document).ready(function() {
  var gaya = document.createElement('style');
  gaya.innerHTML =  "#loadScreen {position:fixed;top:0;left:0; background-color:rgba(255,255,255,.7); display:none;width:100%;height:100%; text-align:center;vertical-align:middle;z-index:1100;}";
  gaya.innerHTML += "#loadScreen div {margin:auto;margin-top:150px;}";
  gaya.innerHTML += "#loadScreen progress {width:80%; max-width:500px;}";
  document.head.appendChild(gaya);
  //http://stackoverflow.com/questions/3097644/can-code-in-a-javascript-file-know-its-own-domain-url
  var path = $('script[src$="myAsyncLoading.js"]').attr('src');
  var mydir= path.split('/').slice(0, -1).join('/')+'/';  // remove last filename part of path
  $("body").append("<div id='loadScreen'><div><img src='"+mydir+"loading.gif'/></div><progress value='100' max='100'></progress></div>");
});

class tr {
  static notifyError(message) {
    $('body').toast({class:'error', message: message, title:'Error', showIcon:'exclamation triangle'});
  }
  static notifyWarning(message) {
    $('body').toast({class:'warning', message: message, showIcon:'info circle'});
  }
  static notifySuccess(message) {
    $('body').toast({class:'success', message: message, title:'Success', showIcon:'thumbs up outline'});
  }
  static loading(Show) {
    if (Show) $("#loadScreen").fadeIn();
    else $("#loadScreen").fadeOut();
  }
  static handleResponse = function(reply,errorcb) {
    try {
      if (reply.result === "error") {
        if(typeof errorcb === 'function') errorcb(reply);
        else this.notifyError(reply.message);
        return false;
      }
      else if (reply.result === "success") { return true; }
      else if (reply.result === "debug") { console.log(reply.data); return false;}
    } catch (e) {
      console.log(reply);
      console.log(e);
      this.notifyError('Unknown Error. Check console log (F12) for details');
      return false;
    }
  }

  static async handleLaravelError(r) {
    try {
      let laraJson = r.responseJSON;
      this.notifyError(laraJson.message);
      for (let err in laraJson.errors) {
        this.notifyWarning(laraJson.errors[err].join(', '));
      }
    } catch (error) { console.log(r.responseText); } //When it is not laraJson, just console log the error.
  }
  static async post(uri,oPost, errorcb) {
    let that = this;
    return new Promise((reso,reje) => {
      that.loading(true);
      $.post(uri, oPost, function(reply,stat,xhr) {
    //    console.log(xhr.getResponseHeader('content-type'));
        if (that.handleResponse(reply,errorcb)) reso(reply);
        else reso(false);
      },'json')
      .always(function() { that.loading(false); })
      .fail(function(r) { that.handleLaravelError(r); reso(false); });
    });
  }

  static async silentPost(uri,oPost,errorcb) {
    let that = this;
    return new Promise((reso,reje) => {
      $.post(uri, oPost, function(reply) {
        if (that.handleResponse(reply,errorcb)) reso(reply);
        else reso(false);
      },'json')
      .fail(function(r) { that.handleLaravelError(r); reso(false); });
    });
  }
  //modifikasi dari http://stackoverflow.com/questions/166221/how-can-i-upload-files-asynchronously-with-jquery
  static async postForm(uri,form,errorcb) { //Form is a DOM object e.g: $("#formLoadFCA")[0]
    return await this.postFormData(uri, new FormData(form),errorcb);
  };
  static async postFormFromObj(uri, obj, errorcb) {
    var formData = new FormData();
    for (var i in obj) formData.append(i, obj[i]);
    return await this.postFormData(uri, formData, errorcb);
  };
  //formData is formData: use: formData = new FormData();  formData.append(key,val);
  //Untuk ambil data gambar: formData.append('gambar', $('#fileInput')[0].files[0]))
  static async postFormData(uri,formData,errorcb) {
    let that = this;
    return new Promise((reso, reje) => {
      that.loading(true);
      $.ajax({
        dataType:'json',
        url:uri, type:'POST',data:formData,contentType:'multipart/form-data',
        error: function(j, stat, error) { /** Handled below at .fail */ },
        success: function(reply) {
          if (that.handleResponse(reply,errorcb)) reso(reply);
          else reso(false);
        },
        xhr: function() {  // Custom XMLHttpRequest
          var myXhr = $.ajaxSettings.xhr();
          if(myXhr.upload){ // Check if upload property exists
              myXhr.upload.addEventListener('progress', that.showProgress, false);
              // For handling the progress of the upload. loadingcb is a function(e). See example below
          }
          return myXhr;
        },
        cache:false, contentType:false, processData:false
      })
      .always(function() { tr.loading(false); })
      .fail(function(r) { that.handleLaravelError(r); reso(false); });
    });
  };
  
  
  static showProgress(e) {
    if (e.lengthComputable){
      let uploadPercentage = parseInt(e.loaded/e.total * 100);
      $("#loadScreen progress").attr("value", uploadPercentage);
    }
  };
}