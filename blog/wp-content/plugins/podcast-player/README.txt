=== Podcast Player ===
Contributors: vedathemes
Tags: podcast, podcasting, rss feed, feed to audio, podcaster
Requires at least: 4.7
Tested up to: 5.3
Requires PHP: 5.6
Stable tag: 2.8.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Host your podcast anywhere, display them only using podcasting feed url. Use custom widget or shortcode to display podcast player anywhere on your site.

== Description ==
An easy way to show and play your podcast episodes using podcasting feed url. It is a must have plugin for your podcast website. Give your listeners an easy access to all your episodes from any page or even from all the pages of your website.

= Podcast player key features =

* Give your listeners an easy access to your podcast episodes.
* Display responsive podcast player just by entering your podcast's feed url.
* Fetch all required details from feed url.
* Option to modify fetched details of your podcast.
* Option to Show or Hide individual player elements.
* Give your listener an option to share your podcast episodes.
* Ajax live search episodes from the podcast.
* It is possible to have multiple instances of podcast player on single page.
* Self adjusting layout according to width of the podcast player.

= Setup Podcast Player Widget =

Display searchable podcast episodes list on any widget area of your website.

Minimum Setup

1. After activating the plugin, visit Appearance > Widgets in admin dashboard.
1. Look for 'Podcast player' widget in left 'Available Widgets' section.
1. Drag the widget to any available sidebar/widget area.
1. Enter feed url in the appropriate field.
1. Click [Save] button.

Advanced Setup

1. After activating the plugin, visit Appearance > Widgets in admin dashboard.
1. Look for 'Podcast player' widget in left 'Available Widgets' section.
1. Drag the widget to any available sidebar/widget area.
1. Enter feed url in the appropriate field.
1. Optionally, click on "Change podcast content" button to customize feed's auto fetched details.
1. Optionally, click on "Show/Hide player items" button to show or hide player elements.
1. Optionally, click on "Podcast player styling" button to customize player's accent color.
1. Optionally, click on "Sort & Filter" button to sort or filter podcast episodes.
1. Click [Save] button.

= Setup Podcast Player Block =

Display searchable podcast episodes list on any post or page. Make sure you have not disabled WordPress latest block editor.

Setup

1. After activating the plugin, visit any post or page's edit screen.
1. In main content area, click on '+' icon to add a new block.
1. Search for 'Podcast Player' block.
1. Enter feed url in the appropriate field. A preview of your podcast player will appear.
1. Click on the podcast player preview.
1. Select appropriate options from the right sidebar to customize the player.
1. Save or Update the post.

= Setup Podcast Player using shortcode =

Minimum Setup

	[podcastplayer feed_url ='']

1. feed_url - Your podcast feed url.

Advanced Setup

	[podcastplayer feed_url ='' number='' podcast_menu='' cover_image_url='' hide_cover='true' hide_description='true' hide_subscribe='true' hide_search='true' hide_loadmore='true' hide_download='true' accent_color='#FFFFFF']Short Description [/podcastplayer]

1. feed_url: Your podcast feed url.
1. number: Number of podcasts episodes to be displayed at a time.
1. podcast_menu: Any previously created WordPress menu's name OR ID OR slug. (optional)
1. cover_image_url: Podcast's cover image url. The image must be from your WP media library. (optional)
1. header_default: (false/true) Show player header items by default
1. hide_title: (false/true) Show / Hide podcast Title in header info section
1. hide_cover: (false/true) Show / Hide podcast cover image
1. hide_description: (false/true) Show / Hide podcast description
1. hide_subscribe: (false/true) Show / Hide podcast subscribe button.
1. hide_search: (false/true) Show / Hide podcast search field.
1. hide_author: (false/true) Show / Hide author/podcaster's name.
1. hide_content: (false/true) Show / Hide podcast episode's content.
1. hide_loadmore: (false/true) Show / Hide podcast load more button.
1. hide_download: (false/true) Show/ Hide podcast episode download link.
1. hide_social: (false/true) Show/ Hide podcast episode social sharing links.
1. accent_color: Podcast player's accent color (Color hex-code to be entered).
1. sortby: Sort podcast episodes (sort_date_desc/sort_date_asc/sort_title_desc/sort_title_asc)
1. filterby: Filter by any string in episode's title
1. Short Description:  Podcast short text description. (optional)

Depricated Shortcode Options (No longer available)

1. skin: dark or light player skin
1. no_excerpt: Podcast description excerpt or full content.

== Installation ==

Installing “Podcast player” can be done either by searching for “Podcast Player” via the “Plugins > Add New” screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the ‘Plugins > Add New > Upload’ in your WordPress dashboard
1. Activate the plugin through the ‘Plugins’ menu in WordPress

== Frequently Asked Questions ==

= Why my latest episode is NOT visible in Podcast Player? =
By default, WordPress cache RSS feeds for 12 hours. Therefore, new episodes will be visible after 12 hours from last update.

= Where can I host my podcast files? =
You can host your podcast files anywhere. This plugin only uses rss feed link to fetch and display your podcast episodes.

= Does it support video podcasts? =
Yes, this plugin supports video podcasts (mp4 format).

= Can it support multiple feeds in a single player? =
No, you cannot use multiple podcast feed urls in one player instance.

= Is it responsive friendly? =
Yes, podcast player is completely mobile responsive. It can even change its layout according to its container width. For example, on larger screen, layout of podcast player on a narrow sidebar will be different than on a wider content section.

= Can I show multiple podcast players on a single page? =
Yes. You can display multiple instances of podcast player on a single page/post.

= I have added podcast player block, but I cannot play episodes in edit screen? =
Podcast player block, which appear in post's or page's edit screen is only for previewing purpose. Though, it will play nicely on front-end.

= Does this plugin respect end-user privacy? =
Podcast player plugin (on its own) does not create and save any cookies and does not use or store end-user's IP address. However, website owners (who uses this plugin to display podcast player on their website) are  solely responsible for all user privacy on their site.

== Screenshots ==
1. Display podcast player using custom widget
2. Display podcast player using shortcode
3. Display podcast player using editor block
4. Podcats player on small size container
5. Podcast player on large size container

== Changelog ==

= 2.8.0 =
* Error Fix: Error in feed URI encoding.

= 2.7.0 =
* Error Fix: Error in feed URI encoding.

= 2.6.0 =
* Add: Preserve older podcast episodes even they are removed from RSS feed.
* Modify: Modify & Improve backend CSS, JS and PHP codes.
* Add: Support new functionality in Podcast Player Pro.

= 2.5.0 =
* Error Fix: Play episodes where audio URL contains query strings.
* Error Fix: Encode audio URI to properly parse episode URL.
* Add: Set custom cover image as video's cover image on page load.
* Add: Support for new features in Podcast player pro.

= 2.4.0 =
* Error Fix: Undefined variable in widget update function.
* Error Fix: Play audio files even if media url contains query strings.
* Error Fix: Minor typographical error in front-end message.
* Error Fix: Fixed background color for Play-Pause button if cover image is available.
* Modify: Display HTML in episode description transcript.
* Modify: Custom sanitization and caching of feed data.
* Modify: Podcast player widget backend code cleanup.
* Modify: Additional css for heading elements in episode description.
* Modify: Minimum PHP requirement has been changed to PHP 5.6+

= 2.3.0 =
* Modify: Accessibility improvements.
* Modify: License changed from GPL2+ to GPL3+.
* Add: Display episode featured image on play pause button.
* Add: Podcast player pro version compatibility.

= 2.2.0 =
* Error Fix: Podcast episodes not playing properly.

= 2.1.0 =
* Error Fix: No podcast player block if legacy player is activated.
* Error Fix: Legacy player fatal error.
* Error Fix: Episode list wrapper height in legacy style correction.
* Modify: Hide close option if header is visible by default.

= 2.0.0 =
* Add: Option to modify audio playback rate.
* Add: Additional podcast player display layout.
* Modify: Major structural changes in backend codes to make plugin more flexible.
* Modify: Major changes in backend functions.

= 1.9.0 =
* Bug Fix: Semi colon and other basic html display error in podcast title.
* Bug Fix: Always load mmerrorfix in header to avoid conflict with other plugins.
* Modify: Display podcast author on narrow width player.

= 1.8.0 =
* Bug Fix: Player not fetching correct media enclosure (if multiple media enclosure).
* Bug Fix: Wrong episode ID in initially loaded episodes.

= 1.7.0 =
* Bug Fix: Styling breaks if more than one player editor block is added.
* Bug Fix: Episode title in Media controls overflow the container.
* Bug Fix: Minor RTL styling error fixed.
* Add: Option to hide podcast header.

= 1.6.0 =
* Add: episode author to the episode list.
* Add: RTL languages support
* Add: Podcats Episodes sort and filter options.
* Add: Option to display a single podcast episode.
* Add: Add podcast player editor block

= 1.5.0 =
* Bug Fix - Fallback to media src if share link is not available.
* Modify - Major styling changes in Podcast Player.
* Modify - Use a custom font stack for the player to reduce font inconsistancy.

= 1.4.0 =
* Bug Fix: Button element styling issue resolved.
* Bug Fix: Bug due to Mediaelement class mutation by mediaelement-migrate script
* Bug Fix: Podcast episode list height on large sreen.
* Bug Fix: Increasing simplebar-placeholder width inside flexbox.
* Remove: Repititive PHP scipt removed from class-podcast-player-display.php
* Remove: Cleaner script removed as it is not required.
* Modify: Minor PHP script improvements.
* Add: Options to download or share podcast episode.
* Compliance with latest WordPress coding standards.

= 1.3.0 =
* Implement OOJS and es-next with webpack and babel setup.
* Scrollbar re-position on load more episodes.
* Styling improvements
* Styles modifications for compatibility with various themes
* Script for removing blank text nodes.

= 1.2.0 =
* Improved media player style and functionality.
* Improved podcast player styling.
* Support for video podcasts.
* Ajax load more episodes in real time.
* Ajax live search episodes
* Display episode content in the player.
* Additional options to show or hide player elements.

= 1.1.0 =
* Option to choose mediaelement.js or html5 audio player.
* Fetch external cover images from url (using shortcode).
* Auto fetch feed items from feed url.
* Podcast player widget's ux improvements.
* Change player layout css for larger wrappers.
* Minor css improvements.

= 1.0.0 =
* First version.

== Upgrade Notice ==

= 1.6.0 =
* Update for many new and improved features.

= 1.5.0 =
* Update required for bug fixes and latest podcast player design.

= 1.4.0 =
* Update required for bug fixes and plugin improvements.

= 1.3.0 =
* Update required for bug fixes and compatibility with various themes.

= 1.2.0 =
* Update required to get better design and functionality of podcast player.

= 1.1.0 =
* Update required for additional features and code optimization.

