import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-cards-section',
  standalone: true,
  imports: [RouterLink, ScrollRevealDirective],
  template: `
    <section class="py-24 lg:py-32 bg-surface border-t border-wood-200">
      <div class="max-w-[100rem] mx-auto px-6 sm:px-12">
        <div class="flex items-center gap-6 mb-16" scrollReveal="fade-up">
          <span class="text-accent uppercase tracking-[0.3em] font-display font-bold text-sm">{{ t(section.name) }}</span>
          <span class="h-px bg-wood-300 flex-1"></span>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          @for (post of section.posts; track post.id) {
            <a [routerLink]="['/', section.slug, post.slug]" class="group block bg-wood-50 border border-wood-200 hover:border-accent p-10 lg:p-14 transition-all duration-500 hover:shadow-xl" scrollReveal="fade-up" [revealDelay]="$index * 150">
              @if (post.image?.url) {
                <div class="aspect-video overflow-hidden mb-8 bg-wood-200">
                  <img [src]="post.image.url" [alt]="t(post.title)" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                </div>
              }
              <h3 class="text-2xl font-display font-bold text-wood-950 mb-4 group-hover:text-accent transition-colors">{{ t(post.title) }}</h3>
              <p class="text-wood-600 font-sans leading-relaxed line-clamp-3 text-sm">{{ t(post.content) }}</p>
            </a>
          }
        </div>
      </div>
    </section>
  `,
})
export class CardsSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  t = t;
}
