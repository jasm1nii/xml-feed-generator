# xml-feed-generator
an RSS/atom feed generator for my personal site :cat: tested to work with PHP version 8.2.

---

## how it works
1. match files in a specificied directory.
2. load the DOM of each file.
3. parse the following elements as children of `<entry>`, in order of priority:

| original HTML                 | feed output                     |
|-------------------------------|---------------------------------|
| 1. `class="p-name"`           | `<title>`                       |
| 2. `<title>` in `<head>`      |                                 |
| 3. the XML feed title         |                                 |
|                               |                                 |
| 1. `datetime` attribute of `class=dt-updated` | `<updated>`     |
| 2. `datetime` attribute of the first `<time>` element |         |
| 3. file modification date, retrieved from the server |          |
|                               |                                 |
| 1. `datetime` attribute of `class=dt-published` | `<published>` |
| 2. `datetime` attribute of the first `<time>` element |         |
| 3. file creation date, retrieved from the server |              |
|                               |                                 |
| 1. `class="p-summary"`        | `<summary>`                     |
| 2. `<meta property="description">` |                            |
|                               |                                 |
| 1. `class="e-content"`        | `<content>`                     |
| 2. `<article>`                |                                 |
|                               |                                 |
| 1. /path/to/blog/entry        | `<id>` and `<link rel="alternate">` |



4. output all of the above into a new file named **articles.xml** (default name, but can be changed).

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
