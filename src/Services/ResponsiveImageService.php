<?php

namespace Taba\Crm\Services;

use Awcodes\Curator\Glide\GlideBuilder;
use Awcodes\Curator\Models\Media;

/**
 * Builds optimized, responsive image URLs (WebP + resized) using Curator's
 * signed Glide endpoint. Centralizes the logic so every API resource that
 * serializes a Media record emits PageSpeed-friendly images:
 *
 *   - a default `src` served as WebP at a sensible width (big byte savings)
 *   - a `srcset` across common widths so the browser picks the right one
 *   - intrinsic `width`/`height` so the browser can reserve space (no CLS)
 *
 * Non-resizable formats (svg, gif, ...) fall back to the original URL.
 */
class ResponsiveImageService
{
    /** Widths (px) generated for the srcset. */
    public const WIDTHS = [400, 800, 1200, 1600, 2000];

    /** Default width used for the plain `src` fallback. */
    public const DEFAULT_WIDTH = 1200;

    /** WebP quality — visually lossless while much smaller than JPEG. */
    public const QUALITY = 78;

    /**
     * Return an array of optimized fields for a Media model, or null when the
     * media is missing. Resizable images get WebP + srcset; others pass through.
     *
     * @return array{src:string,srcset:?string,width:?int,height:?int,original:string,type:?string}|null
     */
    public function forMedia(?Media $media): ?array
    {
        if ($media === null) {
            return null;
        }

        $original = (string) ($media->url ?? '');
        $ext      = strtolower((string) ($media->ext ?? ''));

        // Non-resizable (svg/gif/ico) — serve as-is, no Glide transforms.
        if (! function_exists('is_media_resizable') || ! is_media_resizable($ext)) {
            return [
                'src'      => $original,
                'srcset'   => null,
                'width'    => $media->width,
                'height'   => $media->height,
                'original' => $original,
                'type'     => $media->type,
            ];
        }

        $path = (string) $media->path;

        return [
            'src'      => $this->url($path, self::DEFAULT_WIDTH),
            'srcset'   => $this->srcset($path, (int) $media->width),
            'width'    => $media->width,
            'height'   => $media->height,
            'original' => $original,
            'type'     => 'image/webp',
        ];
    }

    /** Build a single signed Glide WebP URL at the given width. */
    public function url(string $path, int $width): string
    {
        return GlideBuilder::make()
            ->width($width)
            ->format('webp')
            ->quality(self::QUALITY)
            ->toUrl($path);
    }

    /**
     * Build a `srcset` string. Skips widths larger than the source image so we
     * never upscale (which wastes bytes and looks worse).
     */
    public function srcset(string $path, int $intrinsicWidth = 0): string
    {
        $entries = [];
        foreach (self::WIDTHS as $w) {
            if ($intrinsicWidth > 0 && $w > $intrinsicWidth) {
                continue;
            }
            $entries[] = $this->url($path, $w) . ' ' . $w . 'w';
        }

        // Ensure at least one candidate even for tiny source images.
        if (empty($entries)) {
            $entries[] = $this->url($path, $intrinsicWidth ?: self::WIDTHS[0])
                . ' ' . ($intrinsicWidth ?: self::WIDTHS[0]) . 'w';
        }

        return implode(', ', $entries);
    }
}
