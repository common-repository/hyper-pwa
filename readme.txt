=== Hyper PWA ===
Contributors: rickey29
Donate link: https://flexplat.com
Tags: progressive web apps, pwa, add to home screen, a2hs, offline
Requires at least: 5.1
Tested up to: 6.6.2
Requires PHP: 7.2
Stable tag: 4.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provide Manifest and Service Worker, convert WordPress into Progressive Web Apps (PWA).

== Description ==
Hyper PWA plugin is developed based on web.dev and Workbox, provides Manifest and Service Worker -- it converts WordPress into Progressive Web Apps (PWA).  Users can add a website as an App icon to their mobile deveice Home Screen, can even use it during Offline Mode.  It is compatible with OneSignal and Firebase, website owner can send Push Notifications to the App.

Features:
* Provide Manifest
* Provide Service Worker
* Provide Add to Home Screen
* Provide Offline Mode
* Support Push Notifications
* Compatible with OneSignal
* Compatible with Firebase

== Highlight ==
This plugin is relying on a 3rd party Software as a Service -- FlexPlat: https://flexplat.com to generate Manifest and Service Workers related files.  The Terms and Conditions is located at: https://flexplat.com/terms-and-conditions/

In detail, to make PWA working, end users will ask your website to provide Manifest and Service Workers related files:
* hyper-pwa-register.js
* hyper-pwa-service-worker.js
* hyper-pwa-manifest.json
* hyper-pwa-offline.html
* hyper-pwa-unregister.js
* hyper-pwa-a2hs.js
Inside of producing these files within my plugin, my plugin will send necessary parameters to FlexPlat, FlexPlat will build the Service Workers related files based on the received parameters, and return these files to your website.  Then my plugin forwards these files to end users.

== Open Issue ==
None.

== Demo ==
1. https://flexplat.com

== Screenshots ==
1. https://download.flexplat.com/flexplat.png

== Download ==
1. WordPress Plugins Libraries: https://wordpress.org/plugins/hyper-pwa/

== Installation ==
1. Upload the plugin files to the '/wp-content/plugins/hyper-pwa' directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.

== Configuration ==
Go to your website Dashboard Hyper PWA section for detail.

== Upgrade Notice ==
None.

== Frequently Asked Questions ==
Provided at website Dashboard Hyper PWA section FAQ tab.

== Changelog ==

= 4.1.0
(Sun., Oct. 20, 2024)
* Improve according to Plugin Check

= 4.0.0
(Sun., Aug. 25, 2024)
* Retrofit Flx

= 3.5.0
(Tue., Jul. 02, 2024)
* New Feature: Add support to premium account.

= 3.4.0
(Sun., Jun. 02, 2024)
* Bug Fix: A2HS Popup Windows covered by advertisements.

= 3.3.0
(Sun., May 26, 2024)
* New Feature: Subscription

= 3.2.0
(Wed., May 22, 2024)
* New Feature: Support A2HS on iOS
* New Feature: Use PWABuilder as default/fallback PWA
* New Feature: Disable A2HS for default/fallback PWA

= 3.1.0
(Wed., May 01, 2024)
* Bug Fix: TypeError: Cannot read properties of null (reading 'querySelector')
* Bug Fix: Problem with Push Messages

= 3.0.0
(Wed., Apr. 24, 2024)
* Retrofit

= 2.33.0
(Sun., Jun. 11, 2023)
* Improvement: Show Add to Home Screen only on specific url.

= 2.32.0
(Fri., May 05, 2023)
* Bug Fix: CrossOrigin warning message.

= 2.31.0
(Wed., May 03, 2023)
* Improvement: Optimize Service Worker query.

= 2.30.0
(Sun., Apr. 30, 2023)
* Improvement: Add to Home Screen button font family and size.

= 2.29.0
(Sat., Apr. 29, 2023)
* Improvement: Merge Manifest and Server Worker packages.

= 2.28.0
(Fri., Apr. 28, 2023)
* Improvement: Simulate cron job.

= 2.27.0
(Wed., Apr. 26, 2023)
* Bug Fix: Retrieve and Fallback issue.

= 2.26.0
(Mon., Apr. 24, 2023)
* Bug Fix: Can NOT personalize Icons.

= 2.25.0
(Sun., Apr. 23, 2023)
* Bug Fix: Move hyper-pwa-service-worker.js and hyper-pwa-manifest.json to Precache List continue.

= 2.24.0
(Sat., Apr. 22, 2023)
* Bug Fix: Move hyper-pwa-service-worker.js and hyper-pwa-manifest.json to Precache List.

= 2.23.0
(Fri., Apr. 21, 2023)
* Bug Fix: Scheduled update Manifest and Service Worker files.

= 2.22.0
(Thur., Apr. 20, 2023)
* Bug Fix: Add to Home Screen freezed.

= 2.21.0
(Sun., Apr. 16, 2023)
* Improvement: Support CORS.

= 2.20.0
(Mon., Apr. 10, 2023)
* Improvement: Add Manifest supplement text field.

= 2.19.0
(Tue., Apr. 08, 2023)
* Improvement: Deactive when other PWA plugin is active.

= 2.18.0
(Tue., Apr. 04, 2023)
* Improvement: Add version number for JavaScript link.

= 2.17.0
* Improvement: Add Manifest and Service Worker both from SaaS and from local.

= 2.16.0
* Bug Fix: Trying to access array offset on value of type bool in /wp-content/plugins/hyper-pwa/lib/lib.php

= 2.15.0
* Improvement: Change Manifest and Service Worker from SaaS to local.

= 2.14.0
* Bug Fix: Correct wp_enqueue_script() parameters.
* Bug Fix: Correct wp_enqueue_style() parameters.

= 2.13.0
* Improvement: Improve transient refresh.

= 2.12.0
(Fri., Aug. 19, 2022)
* Improvement: Improve cron job.

= 2.11.0
(Wed., May 25, 2022)
* Improvement: Improve plugin setting description.

= 2.10.0 =
(Fri., May 20, 2022)
* Remove: Manifest screenshots
* Remove: Manifest shortcuts
* Remove: Manifest related_applications

= 2.9.0 =
(Fri., Apr. 22, 2022)
* New Feature: Manifest screenshots
* New Feature: Manifest shortcuts
* New Feature: Manifest related_applications

= 2.8.0 =
(Sun., Apr. 10, 2022)
* Improvement: Combine the free and the premium version.

= 2.7.0 =
(Wed., Mar. 30, 2022)
* Improvement: Move Procedure to Tab.
* Bug Fix: Remove "browser primary" from Manifest Display.
* Improvement: Server side security.
* Improvement: Add Diagnosis.
* Improvement: Google Analytics.

= 2.6.0 =
(Sun., Mar. 20, 2022)
* Bug fix: JS version issue.
* Improvement: Manifest & Server Worker refresh issue.

= 2.5.0 =
(Sat., Mar. 19, 2022)
* Improvement: Merge Flex PWA with Hyper PWA.
* Bug fix: Remove "browser primary" from Manifest Display
* Improvement: Move Procedure to Tab

= 2.4.0 =
(Mon., Mar. 07, 2022)
* Improvement: Various optimizations.

= 2.3.0 =
(Thur., Feb. 10, 2022)
* Bug fix: Add to Home Screen icon issue.

= 2.2.0 =
(Thur., Jan. 27, 2022)
* Bug fix: for the error messages in PluginTests.

= 2.1.0 =
(Sun., Jan. 09, 2022)
* Improvement: Update premium features.

= 2.0.0 =
(Fri., Dec. 17, 2021)
* New Feature: Support Id property in manifest.
* Improvement: Rewrite server side with Express.

= 1.19.0 =
(Tue., Nov. 30, 2021)
* New Feature: Traffic balance.
* New Feature: Fallback.

= 1.18.0 =
(Fri., Oct. 29, 2021)
* New Feature: Verify if is compatible with OneSignal.
* New Feature: Verify if is compatible with Firebase.

= 1.17.0 =
(Thur., Oct. 07, 2021)
* New Feature: Support Add to Home screen.

= 1.16.0 =
(Sun., Aug. 29, 2021)
* New Feature: Provide more detail in admin settings page.

= 1.15.0 =
(Wed., Jul. 28, 2021)
* New Feature: Add more functions in admin settings page.

= 1.14.0 =
(Thur., Jul. 08, 2021)
* New Feature: Support Workbox Background Sync.

= 1.13.0 =
(Mon., Jun. 28, 2021)
* Improvement for PluginTests.

= 1.12.0 =
(Mon., Jun. 21, 2021)
* Improvement for Lighthouse Audit.

= 1.11.0 =
(Mon., Jun. 14, 2021)
* New feature: Use corn job to refresh cache.

= 1.10.0 =
(Tue., May 25, 2021)
* Bug fix: nonce not working for multiple users.

= 1.9.0 =
(Mon., May 24, 2021)
* Update according to WordPress Plugin Security guideline.

= 1.8.0 =
(Fri., May 07, 2021)
* New feature: multiple recipes.

= 1.7.0 =
(Fri., Apr. 23, 2021)
* Improve Service Worker recipe.

= 1.6.0 =
(Mon., Apr. 19, 2021)
* Improve Service Worker recipe.

= 1.5.0 =
(Fri., Apr. 09, 2021)
* Improve Service Worker recipe.

= 1.4.0 =
(Sun., Apr. 04, 2021)
* Deactivate Service Worker within Administration Dashboard.

= 1.3.0 =
(Tue., Mar. 30, 2021)
* Provide plugin Settings Page.

= 1.2.0 =
(Thur., Mar. 18, 2021)
* Pass Lighthouse PWA audit.
* Work compatible with AMP.
* Display an Offline Page when network is not available.
* Bypass WordPress Administration Dashboard for PWA.

= 1.1.0 =
(Thur., Mar. 04, 2021)
* Update according to WordPress Plugin Handbook.

= 1.0.0 =
(Tue., Mar. 02, 2021)
* Submission accepted by WordPress Plugin Review Team.

= 0.3.0 =
(Tue., Mar. 02, 2021)
* Update continued according to the comments of WordPress Plugin Review Team.

= 0.2.0 =
(Sat., Feb. 27, 2021)
* Update according to the comments of WordPress Plugin Review Team.

= 0.1.0 =
(Wed., Feb. 21, 2021)
* primary development

== Support ==
Author: Rickey Gu
Web: https://flexplat.com
Email: rickey29@gmail.com
