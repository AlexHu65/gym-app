import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  standalone: true,
  template: `
    <section>
      <h2>Exercise editor</h2>
      <p>Editing exercise: {{ id || 'new' }}</p>
    </section>
  `,
})
export class ExerciseEditorPage {
  id: string | null = null;

  constructor(private readonly route: ActivatedRoute) {}

  ngOnInit(): void {
    this.id = this.route.snapshot.paramMap.get('id');
  }
}
