import { Component, inject, signal, OnInit } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { Title, Meta } from '@angular/platform-browser';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';

@Component({
  selector: 'app-category',
  imports: [RouterLink],
  templateUrl: './category.html',
  styleUrl: './category.scss',
})
export class Category implements OnInit {
  private api = inject(ApiService);
  private route = inject(ActivatedRoute);
  private titleSvc = inject(Title);
  private meta = inject(Meta);

  category = signal<any>(null);
  posts = signal<any[]>([]);
  loading = signal(true);
  t = t;

  ngOnInit() {
    this.route.params.subscribe(params => {
      this.loading.set(true);
      this.api.getCategory(params['slug']).subscribe({
        next: (res: any) => {
          this.category.set(res.category);
          this.posts.set(res.posts || []);
          this.loading.set(false);
          const name = t(res.category?.name) || '';
          const desc = t(res.category?.description) || '';
          this.titleSvc.setTitle(name);
          this.meta.updateTag({ name: 'description', content: desc });
          this.meta.updateTag({ property: 'og:title', content: name });
          this.meta.updateTag({ property: 'og:description', content: desc });
        },
        error: () => this.loading.set(false),
      });
    });
  }

  getImage(post: any): string {
    return post.image?.url || post.images?.[0]?.url || '/assets/images/default.jpg';
  }
}
