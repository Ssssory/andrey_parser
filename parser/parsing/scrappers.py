import re
from dataclasses import dataclass
import navigation.source as src
from bs4 import BeautifulSoup

@dataclass
class AdInfo:
    short_description: str
    district: str
    details: dict
    description: dict
    images: list

class Scrapper:
    def __init__(self):
        self._parser_name = 'html.parser'

    def _get_soup(self, html):
        return BeautifulSoup(html, self._parser_name)

class AdsListScrapper(Scrapper):
    def __init__(self):
        super().__init__()
        self._link_attr = 'href'

    def get_ad_links(self, page):
        links = []
        html = page.get_content()
        soup = self._get_soup(html)

        for tag in soup.find_all(src.Tags.SEARCH_START, class_=src.Css.SEARCH_START):
            url = ''

            if link := tag.find_parent(src.Tags.AD_LINK):
                url = link.attrs.get(self._link_attr, '')

            if url:
                url = f'{src.Urls.DOMAIN}{url}'
                links.append(url)

        return links

class AdScrapper(Scrapper):
    def __init__(self):
        super().__init__()

    def scrap_info(self, page) -> AdInfo:
        html = page.get_content()
        soup = self._get_soup(html)
        short_description, district = self._scrap_header(soup)
        images = self._scrap_images(soup)
        details = self._scrap_details(soup)
        description = self._scrap_description(soup)
        return AdInfo(short_description, district, details, description, images)

    def _scrap_header(self, soup) -> tuple:
        short_description = soup.find(src.Tags.HEADER)
        text_description = short_description.get_text()

        if not short_description:
            raise Exception('Header not found!')

        district = short_description.next_sibling.contents[0]

        return (text_description, district.get_text())

    def _scrap_images(self, soup) -> list:
        tags = soup.find_all(src.Tags.AD_IMAGE, alt=re.compile(src.RegEx.AD_IMAGE))

        if not tags:
            raise Exception('No images found!')

        return [tag['src'] for tag in tags]

    def _scrap_details(self, soup) -> dict:
        tags = soup.find_all(src.Tags.DETAILS, class_=src.Css.DETAILS)

        if not tags:
            raise Exception('Ad details hasn\'t been found')

        details = {}

        for tag in tags:
            detail = tag.contents[0].contents[0]
            bullets = [child.get_text() for child in tag.contents[1].children]

            if not detail or not bullets:
                raise Exception('Ad details contents haven\'t been found')

            details[detail.get_text()] = bullets

        return details

    def _scrap_description(self, soup):
        tag = soup.find(src.Tags.DESCRIPTION_HEADER, class_=src.Css.DESCRIPTION_HEADER)
        if not tag:
            raise Exception('Description header hasn\'t been found')

        description_header = tag.contents[0]

        tags = soup.find_all(src.Tags.DESCRIPTION, class_=src.Css.DESCRIPTION)

        if not tags:
            raise Exception('Description text hasn\'t been found')

        tokens = []

        for tag in tags:
            tokens.append(tag.get_text())

        description = ''

        if len(tags) > 1:
            description = '\n'.join(tokens)
        else:
            description = tokens[0]

        return {description_header.get_text(): description}
