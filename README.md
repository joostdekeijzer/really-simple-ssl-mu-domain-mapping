# Really Simple SSL &amp; WordPress MU Domain Mapping

When [Really Simple SSL](https://wordpress.org/plugins/really-simple-ssl/) and [WordPress MU Domain Mapping](https://wordpress.org/plugins/wordpress-mu-domain-mapping/) both are enabled, non-default domains are first redirected to SSL and then redirected to the default domain. When there is no SSL certificate exists for the non-default domain, the browser generates an error and the redirection stops.

This plugin disables Really Simple SSL for the non-default domains. So **http**://nondefault.example is first redirected to **http**://default.example, which is than redirected to **https**://default.example.
