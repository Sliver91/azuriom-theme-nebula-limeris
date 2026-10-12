/* Nebula Limeris - Limeris.fr by Sliver91 */
(function () {
    'use strict';

    /* The big title is sized to fit the available width, whatever its length. */
    var ratios = { normal: 0.5, grand: 0.68, geant: 1 };

    function fitTitle(title) {
        var inner = title.firstElementChild;
        if (!inner) {
            return;
        }
        var narrow = window.matchMedia('(max-width: 767px)').matches;
        var ratio = narrow ? 1 : (ratios[title.getAttribute('data-nl-fit')] || ratios.grand);

        title.style.fontSize = '100px';
        var width = inner.getBoundingClientRect().width;
        if (width > 0) {
            title.style.fontSize = (100 * title.clientWidth * ratio / width) + 'px';
        }
    }

    function fitAll() {
        document.querySelectorAll('[data-nl-fit]').forEach(fitTitle);
    }

    /* Copy the address, with a fallback for sites served over http. */
    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise(function (resolve, reject) {
            var area = document.createElement('textarea');
            area.value = text;
            area.setAttribute('readonly', '');
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            try {
                document.execCommand('copy') ? resolve() : reject();
            } catch (e) {
                reject(e);
            }
            document.body.removeChild(area);
        });
    }

    function bindCopy(button) {
        var label = button.textContent;
        button.addEventListener('click', function () {
            var done = function () {
                button.textContent = button.getAttribute('data-nl-copied') || label;
                button.classList.add('nl-copied');
                setTimeout(function () {
                    button.textContent = label;
                    button.classList.remove('nl-copied');
                }, 1800);
            };
            copyText(button.getAttribute('data-nl-copy')).then(done, function () {
                window.prompt('', button.getAttribute('data-nl-copy'));
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        fitAll();
        if (document.fonts && document.fonts.load) {
            document.fonts.load('800 100px "Big Shoulders Display"').then(fitAll, fitAll);
            document.fonts.ready.then(fitAll);
        }
        window.addEventListener('load', fitAll);
        window.addEventListener('resize', fitAll);
        document.querySelectorAll('[data-nl-copy]').forEach(bindCopy);
    });
})();
