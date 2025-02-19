  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/froala_editor.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/align.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/char_counter.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/paragraph_format.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/paragraph_style.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/url.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/entities.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/languages/de.js'); ?>"></script>
 
 <script>
      //~ $(function(){
        //~ $('#about_salon')
          //~ .on('froalaEditor.initialized', function (e, editor) {
            //~ $('#about_salon').parents('form').on('submit', function () {
              //~ console.log($('#about_salon').val());
              //~ return false;
            //~ })
          //~ })
          //~ .froalaEditor({enter: $.FroalaEditor.ENTER_P, placeholderText: null})
      //~ });
     $(function (){
      const editorInstance = new FroalaEditor('#about_salon', {
        enter: FroalaEditor.ENTER_P,
        key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
        placeholderText: null,
        language: 'de',
        events: {
          initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
              //console.log(editor.$oel.val())
              e.preventDefault()
            })
          }
        }
      })
    });

  // $(function (){
  //     const editorInstance = new FroalaEditor('#clientNotevalue', {
  //       enter: FroalaEditor.ENTER_P,
  //       key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
  //       placeholderText: null,
  //       language: 'de',
  //       events: {
  //         initialized: function () {
  //           const editor = this
  //           // this.el.closest('form').addEventListener('submit', function (e) {
  //           //   //console.log(editor.$oel.val())
  //           //   e.preventDefault()
  //           // })
  //         }
  //       }
  //     })
  //   });      
  </script>
