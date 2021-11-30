(function($) {

    $(document).ready(function() {

        const url = drupalSettings.path.baseUrl + 'api/nodehide/verify';
        const nid = 1990;
        var $verifyBox = null;
        let $txtCode = $('#txt-node-hide-key');
        var $btnVerify = null;
        let $contentArea = $('.page-content-area');

        const verify = (code) => {
            const data = {
                'nid': nid,
                'code': code,
            };

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        renderNode(data.node)
                    }
                    $verifyBox.fadeOut();
                }
            })
        }

        /**
         * Render : verification box as html
         * @returns string
         */
        const renderVerificationBox = () => {
            if (drupalSettings.node_hide.popup.show === false) return;

            let content = '';

            content += '<div id="nodehide-box">';
            content += '    <div class="content">';
            content += '        <div class="btn-close">[ close ]</div>';
            content += '        <input id="txt-node-hide-key" placeholder="key" />';
            content += '        <button id="btn-verify">Send</button>';
            content += '    </div>';
            content += '</div>';

            $('body').append(content);

            $verifyBox = $('#nodehide-box');
        }

        const renderNode = (content) => {
            $contentArea.html(content);
        }

        /**
         * Event : verify code
         */
         const click_send = () => {
            $btnVerify = $('#btn-verify');
            let $btnClose = $('#nodehide-box .btn-close');
            
            $txtCode = $('#txt-node-hide-key');
            
            if ($btnVerify.length) {
                
                $btnVerify.click(function(e) {
                    e.preventDefault();
        
                    const code = $txtCode.val();
                    verify(code);
                });

                $btnClose.click(function(e) {
                    e.preventDefault();
                    
                    $verifyBox.fadeOut();
                });
            }
        }

        $(document).keyup(function(e) {
            if (e.key === "Escape") { // escape key maps to keycode `27`
                $verifyBox.fadeOut();
           }
       });

        

        const start = () => {
            renderVerificationBox();

            click_send();
        }

        start();

        

        

    });

})(jQuery);