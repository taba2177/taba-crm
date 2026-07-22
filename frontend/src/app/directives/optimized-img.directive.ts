import { Directive, ElementRef, Input, OnInit, Renderer2, booleanAttribute, inject } from '@angular/core';

/**
 * Applies PageSpeed-friendly defaults to every <img> in the app:
 *
 *   - `loading="lazy"` + `decoding="async"` so off-screen images never block
 *     the main thread or delay first paint.
 *   - Sensible `sizes` so the browser can pick the right srcset candidate.
 *
 * Mark the LCP image (hero) and other above-the-fold images with `priority`
 * to opt out of lazy loading and hint the browser to fetch them first.
 *
 * Usage:
 *   <img [src]="..." [srcset]="..." width="1600" height="900" appImg priority>
 *   <img [src]="..." [srcset]="..." appImg>            // lazy by default
 */
@Directive({
  selector: 'img[appImg]',
  standalone: true,
})
export class OptimizedImgDirective implements OnInit {
  private el = inject(ElementRef<HTMLImageElement>);
  private renderer = inject(Renderer2);

  /** Above-the-fold / LCP images: eager + high fetch priority. */
  @Input({ transform: booleanAttribute }) priority = false;

  /** Responsive `sizes` attribute; defaults to full viewport width. */
  @Input() sizes = '100vw';

  ngOnInit(): void {
    const img = this.el.nativeElement;

    if (!img.hasAttribute('decoding')) {
      this.renderer.setAttribute(img, 'decoding', 'async');
    }

    if (this.priority) {
      this.renderer.setAttribute(img, 'loading', 'eager');
      this.renderer.setAttribute(img, 'fetchpriority', 'high');
    } else if (!img.hasAttribute('loading')) {
      this.renderer.setAttribute(img, 'loading', 'lazy');
      this.renderer.setAttribute(img, 'fetchpriority', 'low');
    }

    // Only advertise `sizes` when the image actually has a srcset to choose from.
    if (img.hasAttribute('srcset') && !img.hasAttribute('sizes')) {
      this.renderer.setAttribute(img, 'sizes', this.sizes);
    }
  }
}
