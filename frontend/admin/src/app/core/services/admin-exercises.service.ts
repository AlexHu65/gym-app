import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class AdminExercisesService {
  readonly apiBaseUrl = environment.apiBaseUrl;

  list(): Promise<unknown[]> {
    console.log('Admin list exercises', this.apiBaseUrl);
    return Promise.resolve([]);
  }
}

