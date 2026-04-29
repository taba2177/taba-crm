// frontend/src/app/services/action.service.ts

import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class ActionService {
  private http = inject(HttpClient);
  private url = '/api/v1/actions'; // relative — no environments/ folder in this package

  trackWhatsApp(source?: string): void {
    this.fire('whatsapp', source);
  }

  trackCall(source?: string): void {
    this.fire('call', source);
  }

  trackFormSubmit(source?: string): void {
    this.fire('form', source);
  }

  private fire(action: string, source?: string): void {
    this.http.post(this.url, {
      action,
      source: source ?? null,
      page: window.location.pathname,
    }).subscribe({ error: () => {} }); // fire-and-forget, silent fail
  }
}
