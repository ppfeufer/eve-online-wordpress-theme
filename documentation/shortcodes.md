# Shortcodes

## This theme is providing a couple of shortcodes which can be used to style the content.

### Automatic Grid System

![](images/contentgrid-shortcode-examle.png)

With this theme using the bootstrap framework, of yourse you are able to set up a basic grid for some elements in your content as well.
[contentgrid] is the shortcode that is used for that, together with [gridelement].
While [contentgrid] takes care of drawing the ourt grid frame, the [gridelement] shortcode has the content itself for your element.

Example:
```
[contentgrid classes="col-sm-6"]

[gridelement]

Grid-Element 1

[/gridelement]

[gridelement]

Grid-Element 2

[/gridelement]

[gridelement]

Grid-Element 3

[/gridelement]

[/contentgrid]
```

This will draw a contentgrid with 3 elements. The "classes" attribute is to define the grids behaviour and is using the known bootstrap classes. If that attribute is not used, the system determins the classes on its own. Meaning it will draw a grid of 3 elements per row when you are using a sidebar, and 4 elements a row when there is no sidebar.
