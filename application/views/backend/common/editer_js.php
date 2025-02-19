  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/froala_editor.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/align.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/char_counter.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/code_beautifier.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/code_view.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/colors.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/draggable.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/emoticons.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/entities.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/file.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/font_size.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/font_family.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/fullscreen.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/line_breaker.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/inline_style.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/link.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/lists.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/paragraph_format.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/paragraph_style.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/quick_insert.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/quote.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/table.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/save.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/help.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/print.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/third_party/spell_checker.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/special_characters.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/word_paste.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/url.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/entities.min.js'); ?>"></script>
 
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/image.min.js'); ?>"></script>
 <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/image_manager.min.js'); ?>"></script>
 <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/plugins/video.min.js'); ?>"></script> 


 
 
 <script>

     $(function (){
      const editorInstance = new FroalaEditor('#answer', {
        enter: FroalaEditor.ENTER_P,
        key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
        placeholderText: null,
        events: {
          initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
              console.log(editor.$oel.val())
              //e.preventDefault()
            })
          }
        }
      })
    });

     $(function (){
      const editorInstance = new FroalaEditor('#about_salon', {
        enter: FroalaEditor.ENTER_P,
        key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
        placeholderText: null,
        events: {
          initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
              //console.log(editor.$oel.val())
             // e.preventDefault()
            })
          }
        }
      })
    });


    $(function (){
      const editorInstance = new FroalaEditor('#clientNotevalue', {
        enter: FroalaEditor.ENTER_P,
        key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
        placeholderText: null,
        events: {
          initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
              //console.log(editor.$oel.val())
             // e.preventDefault()
            })
          }
        }
      })
    });
    

    
      
  </script>


