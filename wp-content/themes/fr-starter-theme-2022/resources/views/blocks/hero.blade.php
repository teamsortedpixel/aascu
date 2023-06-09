<x-hero 
    :block-data="$block ?? false" 
    :content="$content" 
    :image="$hero_image ?? false" 
    :image-caption="$image_caption ?? false"
    :circles="$circles ?? []"
    :format="$format ?? false" 
    :start-date="$start_date ?? false"
    :end-date="$end_date ?? false" 
    :timezone-label="$timezone_label ?? false" 
    :publication-date="$publication_date ?? false" 
    :author="$author ?? false" 
    :program="$program ?? false"
    :program-tags="$program_tags ?? false"
    :buttons="$buttons ?? false"
    :formatted-date="$formatted_date ?? false" 
    :formatted-time="$formatted_time ?? false" 
    :type="$type ?? false" />