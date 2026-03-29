import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, shareReplay } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ApiService {
  private http = inject(HttpClient);

  private navigationCache$?: Observable<any>;

  getHome(): Observable<any> {
    return this.http.get('/api/v1/home');
  }

  getNavigation(): Observable<any> {
    if (!this.navigationCache$) {
      this.navigationCache$ = this.http.get('/api/v1/navigation').pipe(
        shareReplay(1)
      );
    }
    return this.navigationCache$;
  }

  getCategory(slug: string): Observable<any> {
    return this.http.get(`/api/v1/categories/${encodeURIComponent(slug)}`);
  }

  getPost(categorySlug: string, postSlug: string): Observable<any> {
    return this.http.get(`/api/v1/categories/${encodeURIComponent(categorySlug)}/${encodeURIComponent(postSlug)}`);
  }

  submitContact(data: { name: string; email: string; message: string; quiz: string }): Observable<any> {
    return this.http.post('/api/v1/contact', data);
  }
}
