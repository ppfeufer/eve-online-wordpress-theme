# Theme Settings

**Quick rundown through the themes settins**

### General Settings

#### Entity Type

This determins if your website is an alliance or a corp website. It is also important to fetch the right Logo.

#### Entity Name

This is the name of your organisation.

#### Your Logo

This will be automatically fetched from CCP's image server once you have entered a name for your organisation.

#### Corp Logos

This setting only has an effect on alliance websites and determins wether or not the corp logos should be displayed in the corp menu's dropdown.

#### Navigation

This setting is a bit hard to describe (at least for me). If activated the theme tries to have the items of your main navigation all at the same width. (I hope you get what I mean :-P)

#### Post Meta

If activated additional meta data will be shown with your blog posts. Such as author and publishing time , categories, tags and so on.

### Background Settings

#### Use Background Image

Self explaining I hope. Tell the theme if it should use a background image or not.

#### Background Image

Even more self explaining, I hope. Select the background image you want to use from the themes default images or select one from your media library or upload your own by clicking the "Upload" button.

#### Background Colour

This is not the websites background color, but the content area background color.

### Slider Settings

These settings will only have an effect if the [Meta Slider](https://wordpress.org/plugins/ml-slider/) Plugin is installed and activated.

#### Default Slider on Front Page

Select your default slider for your front page. This is NOT the blog index page.

#### Pages with Slider / Show only on front page

Show the slider only on the front page. If deactivated the default slider will be active on any page.

### Performance Settings

#### HTML Output

Minifying teh HTML output can speed up your pages loading time. But keep in mind, this feature is experimental. So if you exprience any issues after activating this option, please deactivate it again.

#### Cache

Using an Imagecache instead of always aking CCP's imagesverer for any images might also speed up your website. If activated the theme will download any images that it needs from CCP's image server and save them locally. With this, it is possible that you speed up your website by serving alliance logo, corp logos and pilot avatars locally instead of always asking the Image Server of CCP.

#### Cron Jobs

If this one is activated, a cron job will be installed in your WordPress that will - once a day - clear the image cache to not have thousands of images stored locally on your server. Clearing up the image cache might be a good thing if you have vry limited space with your webhosting.
