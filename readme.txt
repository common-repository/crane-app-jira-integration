=== CRANE APP Jira Integration ===
Contributors: craneapp,kaikrannich
Donate link: https://www.paypal.me/kaikrannich/5
Tags: jira,jql,issue,jira issue,jira rest api,rest api,api,rest,
Requires at least: 4.7.3
Tested up to: 4.7.5
Stable tag: 1.1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.htmlText

Display Jira issues on your website by using JQL for advanced search. This is probably the easiest way to enable external access to Jira issues.

== Frequently Asked Questions ==

= What do I have to consider when using JQL? =

Use [Jira Query Language (JQL)](https://confluence.atlassian.com/jirasoftwarecloud/advanced-searching-764478330.html) to search for Jira issues. All found Jira issues will be displayed on your website. Please be aware of the following notes when using the attribute `jql` as an optional attribute for the shortcode `[crane_app_jira_integration]`.

* Use single quotes instead of double quotes in the JQL query

== Screenshots ==

1. Jira Integration from CRANE APP provides an option page where you can set your authentication and design configurations.
2. Display Jira issues on your website by using a shortcode and its optional attributes.

== Changelog ==

= 1.1.0 =

*Released on 6 June 2017*

* Added error logging of Jira REST API errors

= 1.0.1 =

*Released on 9 April 2017*

* Added Jira issue field `creator`
* Fixed vulnerability to invalid Jira issue fields