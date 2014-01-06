* 1.0.3 (2014-01-06)

  * Feature: `skip_file_exists` bundle config option (@daum)

* 1.0.2 (2012-10-24)

  * Bug fix: Parse options correctly (@kbond)
  * Bug fix: Use `stream_copy_to_stream` instead of `readfile` to prevent memory overflow (@inmarelibero)

* 1.0.1 (2012-10-23)

  * Added support for absolute file names (@inmarelibero)
  * Added hard dependency on `symfony/http-foundation`

* 1.0.0 (2012-10-16)

  * Remove hard dependency on `symfony/framework-bundle`
  * Remove `request` option, inject the request automatically instead
  * Set a functional content-disposition for crappy browsers
