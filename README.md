# simplelightbox Wordpress Plugin
The simplelightbox wordpress plugin brings the [simplelightbox](https://github.com/andreknieriem/simplelightbox) jquery plugin to wordpress.

You can change every option that the lightbox have in the admin panel under Design -> Simplelightbox.

### Install

You can choose between downloading the official Zip directly from wordpress [here](https://wordpress.org/plugins/simplelightbox/) or install it via the plugin manager.

Of course you can download the plugin from here and install this zip file.

### Usage

The Plugin only needs to be installed. After that, the plugin works. It scans every Anchor Tag which linked to an image.

###Options
| Property | Default | Type | Description |
| -------- | ------- | ---- | ----------- |
| sourceAttr | href | string | the attribute used for large images |
| overlay | true | bool | show an overlay or not |
| overlayOpacity | 0.7 | float | the opacity of the overlay |
| spinner | true | bool | show spinner or not |
| nav | true | bool | show arrow-navigation or not |
| navText | ['&larr;','&rarr;'] | array | text or html for the navigation arrows |
| captions | true | bool | show captions if availabled or not |
| captionSelector | 'img' | string | set the element where the caption is. Set it to "self" for the A-Tag itself |
| captionType | 'attr' | string | how to get the caption. You can choose between attr, data or text |
| captionsData | title | string | get the caption from given attribute |
| captionPosition | 'bottom' | string | the position of the caption. Options are top, bottom or outside (note that outside can be outside the visible viewport!) |
| captionDelay | 0 | int | adds a delay before the caption shows (in ms) |
| captionClass | '' | string | add an additional class to the sl-caption |
| close | true | bool | show the close button or not |
| closeText | '×' | string | text or html for the close button |
| swipeClose | true | bool | swipe up or down to close gallery |
| showCounter | true | bool | show current image index or not |
| fileExt | 'png&#124;jpg&#124;jpeg&#124;gif' | regexp or false | list of fileextensions the plugin works with or false for disable the check |
| animationSpeed | 250 | int | how long takes the slide animation |
| animationSlide | true | bool | weather to slide in new photos or not, disable to fade |
| preloading | true | bool | allows preloading next und previous images |
| enableKeyboard | true | bool | allow keyboard arrow navigation and close with ESC key |
| loop | true | bool | enables looping through images |
| rel | false | mixed | group images by rel attribute of link with same selector.
| docClose | true | bool | closes the lightbox when clicking outside |
| swipeTolerance | 50 | int | how much pixel you have to swipe, until next or previous image |
| className: | 'simple-lightbox' | string | adds a class to the wrapper of the lightbox |
| widthRatio: | 0.8 | float | Ratio of image width to screen width |
| heightRatio: | 0.9 | float | Ratio of image height to screen height |
| scaleImageToRatio: | false | bool | scales the image up to the defined ratio size |
| disableRightClick | false | bool | disable rightclick on image or not |
| disableScroll | true | bool | stop scrolling page if lightbox is opened |
| alertError | true | bool | show an alert, if image was not found. If false error will be ignored |
| alertErrorMessage | 'Image not found, next image will be loaded' | string | the message displayed if image was not found |
| additionalHtml | false | string | Additional HTML showing inside every image. Usefull for watermark etc. If false nothing is added |
| history | true | bool | enable history back closes lightbox instead of reloading the page |
| throttleInterval | 0 | int | time to wait between slides |
| doubleTapZoom | 2 | int | zoom level if double tapping on image |
| maxZoom | 10 | int | maximum zoom level on pinching |
| htmlClass | 'has-lightbox' | string or false | adds class to html element if lightbox is open. If empty or false no class is set |
| rtl | false | bool | change direction to rigth-to-left |
| fixedClass | 'sl-fixed' | string | elements with this class are fixed and get the right padding when lightbox opens |
| fadeSpeed | 300 | int | the duration for fading in and out in milliseconds. Used for caption fadein/out too. If smaller than 100 it should be used with animationSlide:false |
| uniqueImages | true | bool | whether to uniqualize images or not |
| focus | true | bool | focus the lightbox on open to enable tab control |
| scrollZoom | true | bool | Can zoom image with mousewheel scrolling |
| scrollZoomFactor | true | bool | How much zoom when scrolling via mousewheel |