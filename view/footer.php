</article>
<div class="footer">
    <div class="copyright"><?= $copyRight ?></div>
    <div class="copyright"><?= $github ?></div>
</div>

<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            // toolbar: [ 'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'bulletedList', 'numberedList', 'blockQuote', 'link', 'undo', 'redo' ],
			toolbar: {
				items: [
					'heading', '|',
					'fontfamily', 'fontsize', '|',
					'alignment', '|',
					'fontColor', 'fontBackgroundColor', '|',
					'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|',
					'link', '|',
					'outdent', 'indent', '|',
					'bulletedList', 'numberedList', '|',
					'code', 'codeBlock', '|',
					'insertTable', '|',
					'blockQuote', '|',
					'undo', 'redo'
				],
				shouldNotGroupWhenFull: true
			}
        } )
        .catch( error => {
            console.error( error );
        } );
</script>

</body>
</html>
