/// <reference types="webpack/module" />

declare namespace sage {
  /**
   * A callback function that is executed when the DOM is ready.
   *
   * @returns void
   */
  interface readyEvent {
    (): void;
  }

  /**
   * Function that executes a {@link readyEvent} when the DOM is ready.
   *
   * @returns void
   */
  interface domReady {
    (callback: readyEvent): void;
  }
}
