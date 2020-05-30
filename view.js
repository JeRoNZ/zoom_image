/*!
 * jQuery FancyZoom Plugin
 * version: 1.0.1 (20-APR-2014)
 * @requires jQuery v1.6.2 or later
 *
 * Examples and documentation at: http://github.com/keegnotrub/jquery.fancyzoom/
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */
!function ($) {
    $.extend(jQuery.easing, {
        easeInOutCubic: function (x, t, b, c, d) {
            return (t /= d / 2) < 1 ? c / 2 * t * t * t + b : c / 2 * ((t -= 2) * t * t + 2) + b
        }
    }), $.fn.fancyZoom = function (settings) {
        function elementGeometry(elemFind) {
            var $elemFind = $(elemFind);
            $elemFind.children().length > 0 && ($elemFind = $elemFind.children(":first"));
            var elemX = $elemFind.offset().left, elemY = $elemFind.offset().top, elemW = $elemFind.width() || 50, elemH = $elemFind.height() || 12;
            return {left: elemX, top: elemY, width: elemW, height: elemH}
        }

        function windowGeometry() {
            var $window = $(window), w = $window.width(), h = $window.height(), x = $window.scrollLeft(), y = $window.scrollTop();
            return {width: w, height: h, scrollX: x, scrollY: y}
        }

        function FancyZoom(opts) {
            function runSpinner(from) {
                preloading ? ($zoom_spin.css("backgroundPosition", "0px " + -50 * pFrame + "px"), pFrame = (pFrame + 1) % 12) : (clearInterval(pTimer), pTimer = 0, pFrame = 0, $zoom_spin.hide(), zoomIn(from))
            }

            function startSpinner(from) {
                $zoom_spin.css({
                    left: wGeometry.width / 2 + wGeometry.scrollX + "px",
                    top: wGeometry.height / 2 + wGeometry.scrollY + "px",
                    backgroundPosition: "0px 0px",
                    display: "block"
                }), pFrame = 0, pTimer = setInterval(function () {
                    runSpinner(from)
                }, 100)
            }

            function zoomIn(from) {
                if (zooming)return !1;
                zooming = !0, $zoom_img.attr("src", from.getAttribute("href")), $zoom_title.html(from.getAttribute("title"));
                var endW = pImage.width, endH = pImage.height, sizeRatio = endW / endH;
                endW > wGeometry.width - options.minBorder && (endW = wGeometry.width - options.minBorder, endH = endW / sizeRatio), endH > wGeometry.height - options.minBorder && (endH = wGeometry.height - options.minBorder, endW = endH * sizeRatio), endH += 40;
                var endTop = wGeometry.height / 2 - endH / 2 + wGeometry.scrollY, endLeft = wGeometry.width / 2 - endW / 2 + wGeometry.scrollX;
                $zoom_close.hide(), $zoom.hide().css({
                    left: eGeometry.left + "px",
                    top: eGeometry.top + "px",
                    width: eGeometry.width + "px",
                    height: eGeometry.height + "px",
                    opacity: "hide"
                }), $zoom.animate({
                    left: endLeft + "px",
                    top: endTop + "px",
                    width: endW + "px",
                    height: endH + "px",
                    opacity: "show"
                }, 200, "easeInOutCubic", function () {
                    $zoom_close.fadeIn(), $zoom_close.click(zoomOut), $zoom.click(zoomOut), $(document).keyup(closeOnEscape), zooming = !1
                })
            }

            function zoomOut() {
                return zooming ? !1 : (zooming = !0, $zoom_close.hide(), $zoom.animate({
                    left: eGeometry.left + "px",
                    top: eGeometry.top + "px",
                    opacity: "hide",
                    width: eGeometry.width + "px",
                    height: eGeometry.height + "px"
                }, 200, "easeInOutCubic", function () {
                    zooming = !1
                }), $zoom.unbind("click", zoomOut), $zoom_close.unbind("click", zoomOut), void $(document).unbind("keyup", closeOnEscape))
            }

            function closeOnEscape(event) {
                27 == event.keyCode && zoomOut()
            }

            var eGeometry, wGeometry, $zoom, $zoom_img, $zoom_close, $zoom_spin, $zoom_title, options = opts, zooming = !1, preloading = !1, pImage = new Image, pTimer = 0, pFrame = 0, self = this;
            $zoom = $("#zoom"), 0 === $zoom.length && ($zoom = $(document.createElement("div")), $zoom.attr("id", "zoom"), $("body").append($zoom)), $zoom_img = $("#zoom_img"), 0 === $zoom_img.length && ($zoom_img = $(document.createElement("img")), $zoom_img.attr("id", "zoom_img"), $zoom.append($zoom_img)), $zoom_title = $("#zoom_title"), 0 === $zoom_title.length && ($zoom_title = $(document.createElement("p")), $zoom_title.attr("id", "zoom_title"), $zoom.append($zoom_title)), $zoom_close = $("#zoom_close"), 0 === $zoom_close.length && ($zoom_close = $(document.createElement("div")), $zoom_close.attr("id", "zoom_close"), $zoom.append($zoom_close)), $zoom_spin = $("#zoom_spin"), 0 === $zoom_spin.length && ($zoom_spin = $(document.createElement("div")), $zoom_spin.attr("id", "zoom_spin"), $("body").append($zoom_spin)), this.preload = function (e) {
                var href = this.getAttribute("href");
                pImage.src !== href && (preloading = !0, pImage = new Image, $(pImage).load(function () {
                    preloading = !1
                }), pImage.src = href)
            }, this.show = function (e) {
                wGeometry = windowGeometry(), eGeometry = elementGeometry(this), self.preload.call(this, e), preloading ? 0 === pTimer && startSpinner(this) : zoomIn(this), e.preventDefault()
            }
        }

        var options = $.extend({minBorder: 90}, settings), fz = new FancyZoom(options);
        this.each(function (e) {
            var $this = $(this);
            $this.mouseover(fz.preload), $this.click(fz.show)
        })
    }
}(jQuery);
