var x1, x2, y1, y2, w, h;
var FormImageCrop = function () {

    var demo3 = function() {
        // Create variables (in this scope) to hold the API and image size
        var jcrop_api,
            boundx,
            boundy,
            $preview = $('#preview-pane'),
            $pcnt = $('#preview-pane .preview-container'),
            $pimg = $('#preview-pane .preview-container img'),

            xsize = $pcnt.width(),
            ysize = $pcnt.height();
            //console.log('init',[xsize,ysize]);

        $('#demo3').Jcrop({
          onChange: updatePreview,
          onSelect: updatePreview,
          aspectRatio: xsize / ysize
        },function(){
          // Use the API to get the real image size
          var bounds = this.getBounds();
          boundx = bounds[0];
          boundy = bounds[1];
          // Store the API in the jcrop_api variable
          jcrop_api = this;
          // Move the preview into the jcrop container for css positioning
          $preview.appendTo(jcrop_api.ui.holder);
        });

        function updatePreview(c){
          if (parseInt(c.w) > 0){
            var rx = xsize / c.w;
            var ry = ysize / c.h;
            x1 = c.x; 
            y1 = c.y; 
            x2 = c.x2; 
            y2 = c.y2; 
            w = c.w; 
            h = c.h;
            $pimg.css({
              width: Math.round(rx * boundx) + 'px',
              height: Math.round(ry * boundy) + 'px',
              marginLeft: '-' + Math.round(rx * c.x) + 'px',
              marginTop: '-' + Math.round(ry * c.y) + 'px'
            });
          }
        };
    }

    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().Jcrop){
                return;
            }
            demo3();
            var btn = '<button type="submit" onclick="setAvatar()" class="btn btn-danger btncrop">Save</button>';
            setBtn(btn);
        }

    };

}();

function setBtn(b){
    if ($(".jcrop-holder")){
      $(".jcrop-holder").append(b);
      return;
    }
    else
      setTimeout(setBtn(b), 300);
}

function setAvatar(){
    data = {imgurl: $("#imgurl").val(), x1: x1, x2: x2, y1: y1, y2: y2, w: w, h: h};
    $.ajax({
        type: 'POST',
        url: $("#imgpath").val(),
        data:{data:data},
        dataType:"json",
        success: function(response){
            if(response.code == 1){
                window.location = $("#editp").val();
            }
        }
    });
}