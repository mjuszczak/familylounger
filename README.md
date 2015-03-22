familylounger
=============

## Site Overview

### Signups

* A user can signup for a  lounge & a user account via the "Create Lounge" form on the home page.
* A user can create just a user account by visiting the lounge s/he desires to join directly, and using the registration form presented there.

### Permissions

* There are two types of lounges: private, public. Currently, lounges are private by default and can only be made public via a request from a Familylounger admin.
* There are three types of users: admin, user, anon (anonymous). Admins can view and do anything within a lounge. Users can view the blog, comment on blog entrees, and view calendar entries. On a public lounge, an anonymous user can view the blog. On a private lounge, an anonymous user cannot access anything. An anonymous user is someone that is not logged in or is not a lounge member.

### Functionality

#### Updates

Provides a list of “events” that have occurred within the lounge. Almost everything you can perform within the lounge will create an “event”. Adding a blog post, commenting on a blog post, adding a calendar entry, when someone joins the lounge, etc.

#### Blog

A minimal blog implementation that supports writing posts and comments.

#### Calendar

A simple calendar for managing upcomming events.

#### People

Manage the lounge members including:

* Manage user permissions
* Accept lounge requests
* Send lounge invitations

#### My Meds

An area for keeping track of the patients medications.

#### To Dos

A simple checklist for managing a task list.

## Deploy

* Pull down familylounger into `familylounger.com.new`

* Make a backup of the existing site

```shell
rm -rf familylounger.com.last
cp -R familylounger.com familylounger.com.last
```

* Copy the existing "config" into place

```shell
cp familylounger.com.last/app/Config/simple-config.php familylounger.com.new/app/Config/simple-config.php
```

* Create a symlink to the data directory

```shell
rm -rf familylounger.com.new/app/webroot/private
ln -s ~/familylounger.data familylounger.com.new/app/webroot/private
```

* Disable the old site

```shell
rm -rf familylounger.com
```

* Apply any schema updates if needed

* Copy the new site into place.

```shell
mv familylounger.com.new familylounger.com
```

