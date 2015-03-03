// Put event listeners into place
        window.addEventListener("DOMContentLoaded", function() {
            // Grab elements, create settings, etc.
            var canvas = document.getElementById("canvas"),
                context = canvas.getContext("2d"),
                video = document.getElementById("video"),
                videoObj = { "video": true },
                errBack = function(error) {
                    console.log("Video capture error: ", error.code); 
                };

            // Put video listeners into place
            if(navigator.getUserMedia) { // Standard
                navigator.getUserMedia(videoObj, function(stream) {
                    video.src = stream;
                    video.play();
                }, errBack);
            } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
                navigator.webkitGetUserMedia(videoObj, function(stream){
                    video.src = window.webkitURL.createObjectURL(stream);
                    video.play();
                }, errBack);
            } else if(navigator.mozGetUserMedia) { // WebKit-prefixed
                navigator.mozGetUserMedia(videoObj, function(stream){
                    video.src = window.URL.createObjectURL(stream);
                    video.play();
                }, errBack);
            }

            // Trigger photo take
            document.getElementById("snap").addEventListener("click", function() {
                context.drawImage(video, 0, 0, 320, 320);
                // Littel effects
                // $('#video').fadeOut('fast');
                $('#video').hide();
                $('#canvas').fadeIn('slow');
                $('#snap').hide();
                $('#new').show();
                // Allso show upload button
                $('#upload').show();
            });
            
            // Capture New Photo
            document.getElementById("new").addEventListener("click", function() {
                $('#video').fadeIn('slow');
                // $('#canvas').fadeOut('slow');
                $('#canvas').hide();
                $('#snap').show();
                $('#new').hide();
                $('#upload').hide();
            });
            // Upload image to sever 
            document.getElementById("upload").addEventListener("click", function(){
                var dataUrl = canvas.toDataURL();

                var member_id = $('#member_id').val();
                $.ajax({
                    url: '/members/upload',
                    type: 'POST',
                    dataType: "json",
                    data: { 
                         imgBase64: dataUrl,
                         member_id: $('#member_id').val()
                    },
                    success: function (data) {
                        if(data.status == 'success'){
                        }
                        // else{
                        //     alert(data.msg);
                        // }
                        $('.fileinput-preview img').attr("src", dataUrl);
                        $('#meta_image').val(member_id + '.png');
                        console.log(data);
                        // alert(data.memberName)
                    }, 
                    error: function (xhr, status, data) {  
                      console.log(status);
                      alert('<?php echo $t->_("Data Not Found"); ?> (' + status + ')'); 
                    }  
                });

            });
        }, false);