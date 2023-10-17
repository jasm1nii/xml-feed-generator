# xml-feed-generator
an RSS/atom feed generator for my personal site :cat:

---

## disclaimer
### this script is not meant to be used as is.
expect to do *a lot* of modification to fit your needs, as this was created specifically for my site structure and blog markup.

---

## how it basically works
1. match files in a specificied directory.
2. load the DOM of each file.
3. parse values retrieved from the following strings/HTML elements, as children of a feed `<entry>`:
   -  `<h2>` as `<title>`
   -  the `datetime` attribute of `<time>` as `<updated>`
   -  `<element class="p-summary">` as `<summary>`
   -  `<element class="e-content">` (or `<element class="entry"` for older markup) as `<content>`
   -  `/path/to/blog/entry` as `<id>`/`<link>`
4. output all of the above in a new file (named `articles.xml`).

---

## usage
configure a cron job on your server to run this script automatically every now and then, and voila~

---

## reference
feel free to check out [the resulting file](https://jasm1nii.xyz/blog/articles/articles.xml) that i've generated.
