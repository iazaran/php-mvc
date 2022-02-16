<footer class="text-center py-2">
    <small>
        Made with ❤️ in <a href="https://giliapps.com" target="_blank" class="text-dark border border-dark rounded-pill px-2 linkButton">GiliApps</a> . Powered
        by <a href="https://github.com/iazaran/php-mvc" target="_blank" class="text-dark border border-dark rounded-pill px-2 linkButton">PHPMVC</a> . <a href="/feed/rss.xml" target="_blank" class="text-secondary">RSS</a>
    </small>
</footer>

<!-- Bootstrap scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<!-- summernote scripts -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<!-- Custom script -->
<script>
    const apiAddress = '<?= URL_ROOT; ?>';
</script>

<!-- LD JSON -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Corporation",
        "name": "GiliApps",
        "legalName" : "GiliApps",
        "url": "<?= URL_ROOT; ?>",
        "logo": "<?= URL_ROOT; ?>/assets/images/logo.png",
        "foundingDate": "2014-01-10",
        "founders": [
            {
                "@type": "Person",
                "name": "Ismael Azaran"
            } ],
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "7035 Balmoral St, Burnaby",
            "addressLocality": "Vancouver",
            "addressRegion": "BC",
            "postalCode": "V5E 1J4",
            "addressCountry": "Canada"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "longitude": "-122.9587149",
            "latitude": "49.22043"
        },
        "hasMap": "https://www.google.com/maps/place/Giliapps/@49.22043,-122.9587149,15z/data=!4m2!3m1!1s0x0:0x7c4b556928c54c6b?sa=X&ved=0ahUKEwiYxKWQ1ejVAhWQZpoKHai5AdAQ_BIIoAEwEg",
        "openingHours": "Mo 09:00-18:00 Tu 09:00-18:00 We 09:00-18:00 Th 09:00-18:00 Fr 09:00-18:00 Sa Closed Su Closed",
        "contactPoint": {
            "@type": "PostalAddress",
            "contactType": "Support",
            "telephone": "[+1-778-806-1308]"
        },
        "sameAs": [
            "https://www.instagram.com/giliapps",
            "https://twitter.com/Giliapps",
            "https://www.facebook.com/Giliapps",
            "http://www.linkedin.com/company/giliapps",
            "https://www.pinterest.com/giliapps",
            "http://www.youtube.com/user/Giliapps",
            "http://giliapps.tumblr.com",
            "https://foursquare.com/v/giliapps/5412e16f498e0e4501f1cdb2",
            "http://vimeo.com/giliapps",
            "https://github.com/Giliapps",
            "https://codepen.io/Giliapps",
            "http://weheartit.com/giliapps"
        ]
    }
</script>

<script src="/js/main.min.js"></script>
</body>

</html>
