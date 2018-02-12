jQuery(document).ready(function (jq) {

    jq(document).on('click', 'a.mppfm-interface-btn', function () {

        var $this = jq(this),
            media_id = $this.data('media-id');

        jq.post(
            MSF.ajax_url, {
                action: 'mppfm_process_req',
                media_id: media_id,
                _nonce: MSF._nonce,
            }, function ( resp ) {
                if ( resp.success ) {
                    $this.html( resp.data.label );
                }
            }, 'json'
        )

        return false;
    })

})
