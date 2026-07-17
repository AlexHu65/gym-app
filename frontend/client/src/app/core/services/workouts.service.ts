import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class WorkoutsService {
  readonly apiBaseUrl = environment.apiBaseUrl;

  createPlan(payload: Record<string, unknown>): Promise<void> {
    console.log('Create workout plan', payload, this.apiBaseUrl);
    return Promise.resolve();
  }

  createSession(payload: Record<string, unknown>): Promise<void> {
    console.log('Create workout session', payload, this.apiBaseUrl);
    return Promise.resolve();
  }
}

