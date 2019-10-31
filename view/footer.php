</article>
<div class="footer">
    <div class="copyright">&copy; mala14 Freemasons.se</div>
    <div class="copyright"><a href="https://github.com/mala14/fmcm"><i class="fab fa-github"></i></a></div>
</div>

<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote' ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        } )
        .catch( error => {
            console.error( error );
        } );
</script>

</body>
</html>
