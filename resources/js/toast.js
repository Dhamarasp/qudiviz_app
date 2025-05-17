/**
 * Show a toast notification
 *
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (success, error, warning, info)
 * @param {number} duration - How long to show the toast in milliseconds
 */
window.showToast = (message, type = "success", duration = 5000) => {
  // This function is defined in the toast component
  // We're just creating a global reference here for convenience
  if (typeof window.showToast === "function") {
    window.showToast(message, type, duration)
  } else {
    console.error("Toast component not loaded")
  }
}
