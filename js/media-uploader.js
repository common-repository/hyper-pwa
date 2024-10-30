function mediaUploader(btn, url) {
  jQuery(document).ready(function($) {
    var mediaUploader;
    $(btn).click(function(e) {
      e.preventDefault();
      if (mediaUploader) {
        mediaUploader.open();
        return;
      }
      mediaUploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
          text: 'Choose Image'
        },
        multiple: false
      });
      mediaUploader.on('select', function() {
        var attachment = mediaUploader.state().get('selection').first().toJSON();
        $(url).val(attachment.url);
      });
      mediaUploader.open();
    });
  });
}


mediaUploader('#icon-192', '#hyper-pwa-icon-192');
mediaUploader('#icon-512', '#hyper-pwa-icon-512');
mediaUploader('#maskable-icon-192', '#hyper-pwa-maskable-icon-192');
mediaUploader('#maskable-icon-512', '#hyper-pwa-maskable-icon-512');

mediaUploader('#screenshot-wide', '#hyper-pwa-screenshot-wide');
mediaUploader('#screenshot-narrow', '#hyper-pwa-screenshot-narrow');
