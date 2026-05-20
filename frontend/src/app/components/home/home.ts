import { Component, inject, signal, OnInit, ViewContainerRef, ViewChild, effect, Type } from '@angular/core';
import { Title, Meta } from '@angular/platform-browser';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';
import { SECTION_COMPONENT_MAP } from '../sections';

@Component({
  selector: 'app-home',
  template: `
    @if (data()) {
      <div class="overflow-x-hidden bg-surface text-wood-900 font-sans selection:bg-wood-900 selection:text-accent group/page">
        <ng-container #sectionOutlet></ng-container>
      </div>
    } @else {
      <div class="flex justify-center items-center h-screen bg-surface">
        <div class="text-center">
          <div class="w-16 h-16 border-4 border-wood-300 border-t-accent rounded-full animate-spin mx-auto mb-6"></div>
          <p class="text-wood-500 font-display tracking-widest uppercase text-sm">Loading...</p>
        </div>
      </div>
    }
  `,
  styles: [`:host { display: block; }`],
})
export class Home implements OnInit {
  private api = inject(ApiService);
  private route = inject(ActivatedRoute);
  private titleSvc = inject(Title);
  private meta = inject(Meta);

  @ViewChild('sectionOutlet', { read: ViewContainerRef, static: false })
  sectionOutlet!: ViewContainerRef;

  data = signal<any>(null);
  settings = signal<any>({});
  isPreview = signal(false);
  previewSection = signal<string | null>(null);

  private mapLegacySectionComponent(component: string): string {
    switch (component) {
      case 'hero-section':
        return 'hero';
      case 'about-section':
        return 'about';
      case 'services-section':
        return 'our-service';
      case 'projects-section':
        return 'our-projects';
      case 'features-section':
        return 'why-choose-us';
      case 'partners-section':
        return 'four-cards';
      default:
        return (component || '').replace('-section', '');
    }
  }

  private normalizeHomePayload(payload: any): any {
    if (!payload || typeof payload !== 'object') {
      return { sections: [] };
    }

    let sections: any[];

    if (Array.isArray(payload.sections)) {
      sections = payload.sections.map((s: any) => ({
        ...s,
        section_component: this.mapLegacySectionComponent(s.section_component || ''),
        posts: Array.isArray(s.posts) ? s.posts : [],
      }));
    } else {
      const categories = Array.isArray(payload.categories) ? payload.categories : [];
      sections = categories.map((category: any) => ({
        ...category,
        section_component: this.mapLegacySectionComponent(category.section_component || ''),
        posts: Array.isArray(category.posts) ? category.posts : [],
      }));
    }

    const featuredPosts = Array.isArray(payload.featured_posts) ? payload.featured_posts : [];
    if (featuredPosts.length > 0 && !sections.some((s: any) => s.section_component === 'hero')) {
      sections.unshift({
        id: 'hero-section',
        name: 'Hero',
        slug: '',
        section_component: 'hero',
        posts: [featuredPosts[0]],
      });
    }

    return { ...payload, sections };
  }

  constructor() {
    effect(() => {
      const payload = this.data();
      const settings = this.settings();
      if (!payload?.sections || !this.sectionOutlet) return;
      this.renderSections(payload.sections, settings);
    });
  }

  ngOnInit() {
    const params = this.route.snapshot.queryParamMap;
    const previewKey = params.get('_preview');
    const previewSectionParam = params.get('_section');

    if (previewKey) {
      this.isPreview.set(true);
      if (previewSectionParam) {
        this.previewSection.set(this.mapLegacySectionComponent(previewSectionParam));
      }
    }

    const source$ = previewKey
      ? this.api.getPreview(previewKey)
      : this.api.getHome();

    source$.subscribe({
      next: (response: any) => {
        this.data.set(this.normalizeHomePayload(response));
      },
      error: (err) => {
        console.error('Error fetching home data:', err);
      },
    });

    this.api.getNavigation().subscribe({
      next: (res: any) => {
        this.settings.set(res.settings || {});
        const name = t(res.settings?.crm_business_name) || t(res.settings?.crm_seo_default_title) || '';
        const desc = t(res.settings?.crm_seo_default_description) || '';
        const image = t(res.settings?.crm_business_logo) || '';
        if (name) this.titleSvc.setTitle(name);
        this.meta.updateTag({ name: 'description', content: desc });
        this.meta.updateTag({ property: 'og:title', content: name });
        this.meta.updateTag({ property: 'og:description', content: desc });
        this.meta.updateTag({ property: 'og:image', content: image });
        this.meta.updateTag({ property: 'og:type', content: 'website' });
      },
    });
  }

  private renderSections(sections: any[], settings: any) {
    if (!this.sectionOutlet) return;
    this.sectionOutlet.clear();

    const targetSection = this.previewSection();

    for (const section of sections) {
      const componentName = section.section_component;

      if (targetSection && componentName !== targetSection) {
        continue;
      }

      const componentType: Type<any> | undefined = SECTION_COMPONENT_MAP[componentName];
      if (!componentType) continue;

      const ref = this.sectionOutlet.createComponent(componentType);
      ref.setInput('section', section);
      ref.setInput('settings', settings);
    }
  }
}
