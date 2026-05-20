import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-service-section',
  standalone: true,
  imports: [RouterLink, ScrollRevealDirective],
  template: `
    <section class="py-24 lg:py-36 bg-wood-950 text-surface border-t-8 border-accent">
      <div class="max-w-[100rem] mx-auto px-6 sm:px-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-24 gap-12 border-b border-wood-800 pb-16" scrollReveal="fade-up">
          <div class="max-w-3xl">
            <div class="flex items-center gap-6 mb-8 transform rotate-1 origin-left">
              <span class="text-accent uppercase tracking-[0.4em] font-display font-bold text-sm">{{ t(section.name) }}</span>
              <span class="h-px bg-wood-800 w-32"></span>
            </div>
            <h2 class="text-4xl lg:text-[4rem] font-display font-extrabold text-surface leading-[1]">
              {{ t(section.subtitle) || t(section.name) }}
            </h2>
          </div>
          @if (section.slug) {
            <a [routerLink]="['/', section.slug]" class="group flex items-center gap-4 text-wood-300 hover:text-accent font-display font-bold transition-colors uppercase tracking-widest text-lg">
              <span>{{ t(settings?.crm_view_all_label) || 'عرض الكل' }}</span>
              <div class="w-16 h-[2px] bg-wood-700 group-hover:bg-accent transition-colors"></div>
            </a>
          }
        </div>

        @if (section.posts && section.posts.length > 0) {
          <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for (post of section.posts; track post.id; let i = $index) {
              <a [routerLink]="['/', section.slug, post.slug]" class="group block border border-wood-800 hover:border-accent p-8 transition-all duration-300" scrollReveal="fade-up" [revealDelay]="i * 100">
                @if (post.image?.url) {
                  <div class="aspect-video overflow-hidden mb-6">
                    <img [src]="post.image.url" [alt]="t(post.title)" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                  </div>
                }
                <h3 class="text-xl font-display font-bold text-surface mb-3 group-hover:text-accent transition-colors">{{ t(post.title) }}</h3>
                <p class="text-wood-400 text-sm leading-relaxed line-clamp-3">{{ t(post.content) }}</p>
              </a>
            }
          </div>
        }
      </div>
    </section>
  `,
})
export class ServiceSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  t = t;
}
