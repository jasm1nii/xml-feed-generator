# xml-feed-generator
an RSS/atom feed generator for my personal site :cat:

---

the basic logic of this PHP script:
1. matches files in a specificied directory
2. parses values retrieved from the following strings/HTML elements, as children of a feed `<entry>`:
   -  `<h2>` as `<title>`
   -  the `datetime` attribute of `<time>` as `<updated>`
   -  `<element class="p-summary">` as `<summary>`
   -  `<element class="e-content">` (or `<element class="entry"` for older markup) as `<content>`
   -  `/path/to/blog/entry` as `<id>`/`<link>`
3. outputs all of the above to `articles.xml` in another directory.

for reference, you can check out [the resulting file](https://jasm1nii.xyz/blog/articles/articles.xml) that i've generated myself.

feel free to modify according to your needs!
