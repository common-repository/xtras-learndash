=== Xtras LearnDash ===

Contributors: ernestortiz
Plugin URI: https://github.com/ernestortiz/xtras-learndash
Donate link: http://paypal.me/ernestortiz
Tags: LearnDash, learning, LMS, courses
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Some xtras for LearnDash (another grid to courses with category/tag filter && show professors)


== Description ==

This plugin is for LearnDash users; it is another grid for the courses (and for professors of the course), with a filter for categories or tags - through the use of shortcodes.


== Installation ==

1. Upload unzipped plugin directory to the /wp-content/plugins/ directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.


== Frequently Asked Questions ==

= Nothing else to do after installation? No options page? =

Nope, you simply upload and activate it; and the plugin do the rest...

= So, how can I use this plugin... =

Using a shortcode:

    To show a filter of course categories names, followed by a two-column grid of all the courses:
        [xtraslms_portfolio]

This shortcode has many arguments; which self-explanatory names.
 'tax' => 'cat', //'tag' or 'cat'


        'progress' => 1,
        'my' => 1,
        'q' => -1, //quantity

        'iclass' => 'thumbnail'

The main argument is SHOW, it decides what to show. It has one of these values: f (for shows only filter), p (for shows only the portfolio of courses), a (for show the authors, it means, the professors), and al (for shows a list of courses per professor). For example:

        To show only a filter:
        [xtraslms_portfolio show="f"]

        To show only a portfolio of courses (it means, image and title) as well as the name of the professor:
        [xtraslms_portfolio show="p,a"]

        To show only a portfolio of the image and name of professors:
        [xtraslms_portfolio show="a"]

You can apply a class to the images, using the argument  ICLASS (its value is 'thumbnail' by default).

You can decide how many columns in the grid (from 1 to 5) with the argument COLS; and how many courses to show, with the argument Q; and the order, using the argument ORDERBY, which has the values 'modified' or 'rand'. For example:

        To show one course, randomly:
        [xtraslms_portfolio show="p" q="1" orderby="rand"]

Back into the filter, you can filter by categories or by tags, using the argument TAX (which can take 'cat' or 'tax' value.

** to be continued **


== Screenshots ==

1. The Filter and the Courses grid...
2. The filter (and the tooltip) in action...
3. Also a grid for professors (please, note that the plugin profile-xtra is used here for the images).


== Donations ==

If you want to help me in writing more code or better poetry, please invite me to a beer (or coffee, maybe) by sending your donation to http://paypal.me/ernestortiz. Thanks in advance.


== Changelog ==

= 1.0.0 =
* Stable Release
