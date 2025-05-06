# Markdown to MediaWiki Conversion Guide

## Basic Syntax Conversion

| Markdown         | MediaWiki       | Notes          |
| ---------------- | --------------- | -------------- |
| # Title          | = Title =       | Level 1 header |
| ## Subtitle      | == Subtitle ==  | Level 2 header |
| **Bold**         | '''Bold'''      | Bold text      |
| _Italic_         | ''Italic''      | Italic text    |
| - List item      | \* List item    | Bullet points  |
| [Link Text](url) | [[Link Text]]   | Internal links |
| [Link Text](url) | [url Link Text] | External links |

## Spell Template Conversion

1. Replace the first `#` with `=`
2. Replace `##` with `==`
3. Replace `**` with `'''`
4. Keep the bullet points as is (they work in both formats)
5. For internal links, change `[Link Text](url)` to `[[Link Text]]`

## Example Conversion

Markdown:

```markdown
# Bless

**Sphere:** Special  
**Level:** 1  
**Range:** 60 yds
```

MediaWiki:

```mediawiki
= Bless =

'''Sphere:''' Special
'''Level:''' 1
'''Range:''' 60 yds
```

## Tips for Easy Conversion

1. Use consistent formatting in your Markdown files
2. Keep internal links in the format `[[Page Name]]` even in Markdown
3. Use bullet points with `-` as they work in both formats
4. Add a blank line after headers
5. Use double spaces at the end of lines for line breaks

## Automated Conversion

You can use tools like `pandoc` to automate the conversion:

```bash
pandoc -f markdown -t mediawiki input.md -o output.mediawiki
```

Or use online converters like:

- https://www.mediawiki.org/wiki/Extension:Markdown
- https://github.com/outofcontrol/mediawiki-markdown-converter
