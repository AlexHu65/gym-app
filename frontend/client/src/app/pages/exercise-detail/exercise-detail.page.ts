import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  standalone: true,
  template: `
    <section>
      <h2>Exercise detail</h2>
      <p>Exercise ID: {{ id }}</p>
      <p>This screen will render media, translations and instructions from the API.</p>
    </section>
  `,
})
export class ExerciseDetailPage {
  id: string | null = null;

  constructor(private readonly route: ActivatedRoute) {}

  ngOnInit(): void {
    this.id = this.route.snapshot.paramMap.get('id');
  }
}
