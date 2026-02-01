function hideMeta() {
    document.querySelectorAll('#postbox-container-2 .postbox').forEach(box => {
        box.style.display = 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {

    hideMeta();

    const metaboxGroups = {
        'mostrar-style': [
            'sltk_change_color',
            'sltk_change_padding',
            'sltk_change_speed',
            'sltk_change_gap',
            'sltk_change_border',
            'sltk_change_slidesPerView',
            'sltk_change_blackAndWhite',
            'sltk_change_stopSlider'
        ],
        'mostrar-height': [
            'sltk_change_height',
            'sltk_change_height_pictures'
        ],
        'mostrar-width': [
            'sltk_change_width',
            'sltk_change_width_pictures',
            'sltk_change_centerSlider'
        ],
        'mostrar-media': ['sltk_add_media']
    };

    Object.keys(metaboxGroups).forEach(function(buttonId) {
        const button = document.getElementById(buttonId);
        const postbox = document.getElementById('postbox-container-2');

        if (button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                postbox.style.display = 'block';
                
                hideMeta(); 

                // Reset estilos de los botones
                Object.keys(metaboxGroups).forEach(function(otherButton){
                    const otherBtn = document.getElementById(otherButton);
                    if(otherBtn){
                        otherBtn.style.color = 'black';
                        otherBtn.style.fontWeight = 'normal';
                        otherBtn.style.borderColor = '#c9d1d9';
                    }
                });

                // Activa los metaboxes correspondientes
                metaboxGroups[buttonId].forEach(function(id) {
                    const metabox = document.getElementById(id);
                    if (metabox) {
                        metabox.style.display = 'block';
                        button.style.color = '#4949c6';
                        button.style.borderColor = '#4949c6';
                        button.style.fontWeight = 'bold';
                    }
                });
            });
        }
    });

});
