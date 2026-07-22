import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { LucideAngularModule, ShieldCheck } from 'lucide-angular';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { OptimizedImgDirective } from '../../directives/optimized-img.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-projects-section',
  standalone: true,
  imports: [RouterLink, LucideAngularModule, ScrollRevealDirective, OptimizedImgDirective],
  template: `
    <section class="py-24 bg-wood-50 border-t-4 border-accent">
      <div class="max-w-[100rem] mx-auto px-6 sm:px-12">
        <div class="flex items-center justify-between mb-20 pb-12 border-b border-wood-200" scrollReveal="fade-up">
          <h2 class="text-3xl lg:text-5xl font-display font-black text-wood-950">{{ t(section.name) }}</h2>
          @if (section.slug) {
            <a [routerLink]="['/', section.slug]" class="text-wood-600 hover:text-accent uppercase tracking-widest font-display font-bold text-sm transition-colors border border-wood-300 px-8 py-4 rounded-full hover:border-accent">
              {{ t(settings?.crm_browse_all_label) || 'تصفح الكل' }}
            </a>
          }
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          @for (post of section.posts; track post.id; let i = $index) {
            <a [routerLink]="['/', section.slug, post.slug]" class="group block relative overflow-hidden bg-surface border border-wood-200 hover:border-accent hover:shadow-xl transition-all duration-500" scrollReveal="fade-up" [revealDelay]="i * 100">
              @if (post.image?.url) {
                <div class="aspect-[4/3] overflow-hidden">
                  <img [src]="post.image.url" [srcset]="post.image.srcset || null"
                       [attr.width]="post.image.width" [attr.height]="post.image.height"
                       [alt]="t(post.title)" appImg sizes="(max-width: 768px) 100vw, 33vw"
                       class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110" />
                </div>
              } @else {
                <div class="aspect-[4/3] bg-gradient-to-br from-wood-100 to-wood-200 flex items-center justify-center">
                  <lucide-icon [img]="ShieldCheckIcon" class="w-12 h-12 text-wood-300"></lucide-icon>
                </div>
              }
              <div class="p-6">
                <h3 class="text-lg font-display font-bold text-wood-950 mb-3 group-hover:text-accent transition-colors">{{ t(post.title) }}</h3>
                <p class="text-wood-600 text-sm leading-relaxed line-clamp-2">{{ t(post.content) }}</p>
              </div>
            </a>
          }
        </div>
      </div>
    </section>
  `,
})
export class ProjectsSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  readonly ShieldCheckIcon = ShieldCheck;
  t = t;
}
