import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class ExercisesService {
  readonly apiBaseUrl = environment.apiBaseUrl;

  list(params: Record<string, string | number | undefined> = {}): Promise<unknown[]> {
    console.log('List exercises', params, this.apiBaseUrl);
    return Promise.resolve([]);
  }

  detail(id: string): Promise<unknown> {
    console.log('Exercise detail', id, this.apiBaseUrl);
    return Promise.resolve({ id });
  }
}

