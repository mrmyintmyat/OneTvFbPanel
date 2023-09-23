def get_live_matches():
    return get_live_sports("https://bingsport.com/high-light")

def get_live_sports(url):
    html_content = fetch_html(url)
    soup = BeautifulSoup(html_content, 'html.parser')
    live_matches = []

    for match_element in soup.select('div.list-match-sport-live-stream'):
        for anchor_tag in match_element.select('a'):
            match_url = anchor_tag['href']

            try:
                # Details for home team
                home_team_name = anchor_tag.find('div', class_='left-team').find('div',class_='txt-team-name').getText()
                home_team_logo = anchor_tag.find('div', class_='left-team').img['data-src']

                # Details for away team
                away_team_name = anchor_tag.find('div', class_='right-team').find('div',class_='txt-team-name').getText()
                away_team_logo = anchor_tag.find('div', class_='right-team').img['data-src']

                # Other info
                match_time_element = anchor_tag.find('div', class_='time-match')

                is_live = anchor_tag.find('div', class_='txt-vs').getText()
                if 'isLive' in is_live:
                    match_status = "Live"
                else:
                    match_status = is_live.replace('\n','')

                match_time = match_time_element.get('data-timestamp')
                if match_time is None:
                    match_time = match_time_element.find('span', class_='txt_time').get('data-timestamp')
                else:
                    match_time = match_time

                if match_time is None:
                    match_time = "0"

                league_name = anchor_tag.find('div', class_='league-name').text.strip()

                match_details = {
                    "match_time": match_time,
                    "home_team_name": home_team_name.replace('\n',''),
                    "home_team_logo": home_team_logo,
                    "away_team_name": away_team_name.replace('\n',''),
                    "away_team_logo": away_team_logo,
                    "league_name": league_name,
                    "match_status": match_status,
                    "servers": get_video_details(match_url)
                }

                live_matches.append(match_details)
            except:
                pass

    return live_matches

def check_image(url):
    response = requests.get(url)
    if response.status_code == 200:
         return url;
    elif response.status_code == 404:
        return 'https://mtek3d.com/wp-content/uploads/2018/01/image-placeholder-500x500-300x300.jpg'
    else:
        return 'https://mtek3d.com/wp-content/uploads/2018/01/image-placeholder-500x500-300x300.jpg'

def get_video_details(match_url):
    html_source = fetch_html(match_url)
    item_servers = extract_mp4_urls(html_source)

    server_list = []

    for i, item_server in enumerate(item_servers, start=1):
        server_url = item_server
        if server_url == [] or server_url == None:
            server_details = {
                'name': f'Default Server {i}',
                'url': 'https://static.videezy.com/system/resources/previews/000/047/490/original/200511-ComingSoon.mp4',
                'header': {'referer': 'https://fotliv.com/'}
            }
        else:
            server_details = {
                'name': f'Server {i}',
                'url': server_url,
                'header': {'referer': 'https://live-streamfootball.com/'}
            }
        server_list.append(server_details)

    return server_list

def extract_mp4_urls(html_content):
    pattern = r'(https?://[^\s"\'<>]+\.mp4)'
    mp4_urls = re.findall(pattern, html_content)
    return mp4_urls

def extract_ld_json_data(html_content):
    soup = BeautifulSoup(html_content, 'html.parser')
    script_tags = soup.find_all('script', {'type': 'application/ld+json'})

    ld_json_data = []
    for script_tag in script_tags:
        try:
            ld_json_data.append(json.loads(script_tag.string))
        except json.JSONDecodeError:
            # Handle JSON decoding errors, if any
            pass

    return ld_json_data


def fetch_html(url):
    response = requests.get(url)
    return response.text
