# xml-feed-generator
an RSS/atom feed generator for my personal site :cat:

---

## disclaimer
**this script is not meant to be used as is (yet).**

expect to do *a lot* of modification to fit your needs, as this was originally tailored for my site structure and blog markup.

---

## how it works
1. match files in a specificied directory.
2. load the DOM of each file.
3. parse values retrieved from the following strings/HTML elements (prioritizing [h-entry markup](https://microformats.org/wiki/h-entry)), as children of a feed `<entry>`:
   -  `<element class=p-name>` (or `<h2>`) as `<title>`
   -  the `datetime` attribute of `<time>` (or the file creation date) as `<updated>`
   -  `<element class="p-summary">` as `<summary>`
   -  `<element class="e-content">` (or `<article>`) as `<content>`
   -  `/path/to/blog/entry` as `<id>` and `<link>`
4. output all of the above into a new file named **articles.xml**.

## ways to use
- configure a cron job on your web server to run automatically every now and then.
   - if you manage your site with cPanel, [here's how to do that](https://docs.cpanel.net/cpanel/advanced/cron-jobs/).
 
or

- run the script locally on your machine.
   - you can open the file on a local server like [five server](https://marketplace.visualstudio.com/items?itemName=yandeu.five-server) for vscode or [XAMPP](https://www.apachefriends.org/index.html).

---

## example output
feel free to check out [the resulting file](https://jasm1nii.xyz/blog/articles/articles.xml) that i've generated.

## alternatives
- [cjwainwright/FeedGenerator](https://github.com/cjwainwright/FeedGenerator)
