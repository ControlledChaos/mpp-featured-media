jQuery(document).ready(function (jq) {

    jq(document).on('click', 'a.mppfm-featured-btn', function () {

        var $this = jq(this);

        jq.post(
            MPP_FEATURED_MEDIA.ajax_url, {
                action: 'mppfm_mark_featured_unfeatured',
                media_id: $this.data('media-id'),
                _nonce: $this.data('nonce'),
            }, function ( resp ) {
                if ( resp.success ) {
                    $this.html( resp.data.label );
                }
            }, 'json'
        );

        return false;
    });
});
