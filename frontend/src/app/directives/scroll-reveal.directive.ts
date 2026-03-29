import { Directive, ElementRef, Input, OnInit, OnDestroy, inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

@Directive({
  selector: '[scrollReveal]',
  standalone: true,
})
export class ScrollRevealDirective implements OnInit, OnDestroy {
  private el = inject(ElementRef);
  private platformId = inject(PLATFORM_ID);
  private observer?: IntersectionObserver;

  @Input() scrollReveal: 'fade-up' | 'fade-down' | 'fade-left' | 'fade-right' | 'zoom-in' | 'flip-up' | '' = 'fade-up';
  @Input() revealDelay = 0;
  @Input() revealDuration = 800;
  @Input() revealOnce = true;

  ngOnInit() {
    if (!isPlatformBrowser(this.platformId)) return;

    const el = this.el.nativeElement as HTMLElement;
    const effect = this.scrollReveal || 'fade-up';

    // Set initial hidden state
    el.style.opacity = '0';
    el.style.transition = `opacity ${this.revealDuration}ms cubic-bezier(0.19, 1, 0.22, 1) ${this.revealDelay}ms, transform ${this.revealDuration}ms cubic-bezier(0.19, 1, 0.22, 1) ${this.revealDelay}ms`;

    switch (effect) {
      case 'fade-up':
        el.style.transform = 'translateY(60px)';
        break;
      case 'fade-down':
        el.style.transform = 'translateY(-60px)';
        break;
      case 'fade-left':
        el.style.transform = 'translateX(60px)';
        break;
      case 'fade-right':
        el.style.transform = 'translateX(-60px)';
        break;
      case 'zoom-in':
        el.style.transform = 'scale(0.85)';
        break;
      case 'flip-up':
        el.style.transform = 'perspective(800px) rotateX(15deg) translateY(40px)';
        break;
      default:
        el.style.transform = 'translateY(60px)';
    }

    this.observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0) translateX(0) scale(1) rotateX(0)';
            if (this.revealOnce) {
              this.observer?.unobserve(el);
            }
          } else if (!this.revealOnce) {
            el.style.opacity = '0';
            switch (effect) {
              case 'fade-up': el.style.transform = 'translateY(60px)'; break;
              case 'fade-down': el.style.transform = 'translateY(-60px)'; break;
              case 'fade-left': el.style.transform = 'translateX(60px)'; break;
              case 'fade-right': el.style.transform = 'translateX(-60px)'; break;
              case 'zoom-in': el.style.transform = 'scale(0.85)'; break;
              case 'flip-up': el.style.transform = 'perspective(800px) rotateX(15deg) translateY(40px)'; break;
            }
          }
        });
      },
      { threshold: 0.1, rootMargin: '0px 0px -50px 0px' }
    );

    this.observer.observe(el);
  }

  ngOnDestroy() {
    this.observer?.disconnect();
  }
}
