<?php

namespace App;

/**
 * Image ratio
 *
 * @param int $dimension
 * @param string $type
 *
 * @return float
 */
function ratio(int $dimension, string $type = 'standard'): float
{
    switch ($type) {
        case 'free':
            $ratio = 0;
            break;

        case 'wide':
            $ratio = 9 / 16;
            break;

        case 'square':
            $ratio = 1;
            break;

        case 'portrait':
            $ratio = 4 / 3;
            break;

        default:
            $ratio = 3 / 4;
            break;
    }

    return $ratio ? round($dimension * $ratio, 3) : 0;
}

/**
 * Reemove useless default image sizes
 * and change sizes if needed
 *
 * @return void
 */
function reset_image_sizes(): void
{
    /**
     * Remove unused
     */
    // remove_image_size('2048x2048');
    remove_image_size('1536x1536');

    /**
     * Update default
     */
    update_option('thumbnail_size_w', 320);
    update_option('thumbnail_size_h', ratio(320, 'square'));
    update_option('thumbnail_crop', 1);

    update_option('medium_size_w', 1024);
    update_option('medium_size_h', ratio(1024));

    update_option('medium_large_size_w', 1280);
    update_option('medium_large_size_h', ratio(1280));

    update_option('large_size_w', 1600);
    update_option('large_size_h', ratio(1600));

    update_option('full_size_w', 1920);
    update_option('full_size_h', ratio(1920));
}

/**
 * Enqueue custom image sizes
 *
 * @return void
 */
function set_image_sizes(): void
{
    $formats = ['free', 'wide', 'square', 'portrait'];
    $crop = array('center', 'center');

    add_image_size('small', 640, ratio(640), $crop);

    foreach ($formats as $format) {
        add_image_size($format . '_thumbnail', 320, ratio(320, $format), $crop);
        add_image_size($format . '_small', 640, ratio(640, $format), $crop);
        add_image_size($format . '_medium', 1024, ratio(1024, $format), $crop);
        add_image_size($format . '_large', 1280, ratio(1280, $format), $crop);
        add_image_size($format, 1920, ratio(1920, $format), $crop);
        add_image_size($format . '_hq', 2048, ratio(2048, $format), $crop);
    }
}

/**
 * Get attachment
 *
 * @param string $id
 * @param string $size
 * @param string|null $format
 *
 * @return string
 */
function get_attachment(string $id, string $size, string $format = null): string
{
    $attachment = $size;

    if ($format) {
        $attachment = $format . '_' . $size;
    }

    return wp_get_attachment_image_srcset($id, $attachment);
}

/**
 * Get media
 *
 * @param string $id
 * @param string $type
 * @param boolean $lazy
 * @param string|null $alt
 * @param array|null $attributes
 * @param string|null $caption
 *
 * @return string
 */
function get_media(
    string $id,
    string $type = 'standard',
    bool $lazy = true,
    ?string $alt = null,
    ?array $attributes = null,
    ?string $caption = null
): string {
    $src = wp_get_attachment_image_src($id, 'full');

    if ($src) {
        $orientation = $src[1] === $src[2] ? 'square' : ($src[1] > $src[2] ? 'landscape' : 'portrait');

        switch ($type) {
            case 'cover':
                $srcset = get_attachment($id, 'medium');
                $sizes = '100vw';
                break;

            case 'half':
                $srcset = get_attachment($id, 'medium', 'portrait');
                $sizes = '(max-width: 1023px) 100vw, (max-width: 1279px) 50vw, 1024px';
                break;

            default:
                $srcset = get_attachment($id, 'medium');
                $sizes = wp_calculate_image_sizes('large', null, null, $id);
                break;
        }

        if (!$srcset) {
            $srcset = $src[0];
        }

        if (get_post_mime_type($id) === 'image/svg+xml') {
            $media = '<img class="media--element-svg" src="' . $src[0] . '">';
        } else {
            $media = '<figure class="media--element ' . $orientation . ' ' . $type . '"';
            $media .= ' data-width="' . $src[1] . '" data-height="' . $src[2] . '"';
            $media .= ' data-type="' . get_post_mime_type($id) . '">';
            $media .= '<picture class="media--object">';

            if (get_post_mime_type($id) !== 'image/gif') {
                $media .= '<source';
                $media .= ' sizes="' . $sizes . '"';
                // $media .= ' srcset="' . preg_replace(array('/.jpg /', '/.png /'), '.webp ', $srcset) . '"';
                $media .= ' srcset="' . $srcset . '"';
                $media .= '>';
            }

            $media .= '<img src="' . $src[0] . '"';

            if ($alt) {
                $media .= ' alt="' . $alt . '"';
            } else {
                $media .= ' alt=""';
            }

            if ($lazy) {
                $media .= ' loading="lazy"';
            }

            if ($attributes) {
                if (is_array($attributes)) {
                    foreach ($attributes as $key => $attribute) {
                        $media .= ' ' . $key . '="' . $attribute . '"';
                    }
                } else {
                    $media .= ' ' . $attributes;
                }
            }

            $media .= '>';
            $media .= '</picture>';

            if ($caption && !empty($caption)) {
                $media .= '<figcaption>' . $caption . '</figcaption>';
            }

            $media .= '</figure>';
        }
    } else {
        $media = '<div class="media--element"><div class="media--object media--placeholder"></div></div>';
    }

    return $media;
}
