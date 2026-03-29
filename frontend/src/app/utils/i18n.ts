/** Helper to extract localized text from {en, ar} objects */
export function t(obj: any, fallback = ''): string {
  if (!obj) return fallback;

  let parsedObj: any = obj;

  // try to parse if it's a JSON string
  if (typeof obj === 'string') {
    try {
        parsedObj = JSON.parse(obj);
    } catch(e) {
        return obj;
    }
  }

  // Determine the preferred content
  const content = parsedObj.ar || parsedObj.en || parsedObj;

  // If the content is an array of blocks (e.g. Filament editor blocks)
  if (Array.isArray(content)) {
    const raw = content.map(block => block?.data?.content || '').join('\n\n');
    return stripMarkdown(raw);
  }

  return (typeof content === 'string') ? content : fallback;
}

/** Strip markdown syntax for plain text display */
function stripMarkdown(text: string): string {
  return text
    .replace(/^#{1,6}\s+/gm, '')   // remove ## headers
    .replace(/\*\*(.*?)\*\*/g, '$1') // remove **bold**
    .replace(/\*(.*?)\*/g, '$1')     // remove *italic*
    .replace(/^[-*+]\s+/gm, '')      // remove list markers
    .replace(/\[([^\]]+)\]\([^)]+\)/g, '$1') // [text](url) → text
    .replace(/\n{3,}/g, '\n\n')      // collapse multiple newlines
    .trim();
}
